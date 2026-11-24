<?php

namespace Modules\Appointment\Http\Controllers\Voip;

use App\Http\Controllers\Controller;
use App\Support\VoipIntegration;
use App\User;
use App\UserMeta;
use Illuminate\Http\Request;
use Modules\Appointment\Entities\Appointment;
use Modules\Appointment\Entities\AppointmentUser;

/**
 * Read-only VoIP API for the call-center panel.
 * Auth: Authorization: Bearer <token> from `php artisan voip:token`.
 */
class VoipAppointmentController extends Controller
{
    private const DIGITS = [
        '۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4', '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9',
        '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4', '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9',
    ];

    private const STATUS = [
        AppointmentUser::STATUS_PENDING => 'در انتظار',
        AppointmentUser::STATUS_OK => 'تایید شده',
        AppointmentUser::STATUS_CANCELED => 'کنسل شده',
        AppointmentUser::STATUS_DELETED => 'حذف شده',
        AppointmentUser::STATUS_PANAHI => 'پناهی',
        AppointmentUser::STATUS_HAS_IDENTIFIERـCODE => 'کد معرف',
        AppointmentUser::STATUS_PENDING_CALL => 'تلفنی بدون وقت',
    ];

    private const KIND = [
        AppointmentUser::KIND_MAIN_APPOINTMENT => 'اصلی',
        AppointmentUser::KIND_SUBSIDIARY_APPOINTMENT => 'بین مریض',
    ];

    /** @see Appointment::$appointmentType */
    private const CATEGORY = [
        1 => 'نوبت اولی',
        2 => 'نوبت بازگشتی',
        3 => 'نوبت ترمیمی',
        4 => 'نوبت های افراد شاکی',
    ];

    public function me(Request $request)
    {
        $ctx = $this->auth($request);
        $doctor = $ctx['doctor_id'] ? User::find($ctx['doctor_id']) : null;

        $conn = VoipIntegration::publicSettings();

        return response()->json([
            'ok' => true,
            'provider' => 'kamilo',
            'doctor_id' => $ctx['doctor_id'],
            'doctor_name' => $doctor ? trim((string) $doctor->full_name) : null,
            'scope' => $ctx['doctor_id'] ? 'doctor' : 'all',
            'connection' => [
                'enabled' => $conn['enabled'],
                'panel_url' => $conn['panel_url'],
                'username' => $conn['username'],
                'has_password' => $conn['has_password'],
            ],
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Appointment list for the token's doctor scope (or all).
     * Query: date=Y/m/d (Jalali) | from & to (Jalali Y/m/d) | limit (default 200, max 500)
     *        confirmed=1 to only confirmed rows | status= int filter
     */
    public function appointments(Request $request)
    {
        $ctx = $this->auth($request);
        $q = AppointmentUser::query()
            ->with(['user', 'doctor', 'appointmentPart', 'agent'])
            ->orderBy('year')->orderBy('month')->orderBy('day')
            ->orderByRaw('COALESCE(confirmed_time_from, time_from)')
            ->orderBy('id');

        if ($ctx['doctor_id']) {
            $q->where('doctor_id', $ctx['doctor_id']);
        }

        if ($request->filled('date')) {
            $p = $this->jalaliParts($request->get('date'));
            abort_unless($p, 422, 'تاریخ نامعتبر است (YYYY/MM/DD شمسی)');
            $q->where([['year', $p[0]], ['month', $p[1]], ['day', $p[2]]]);
        } else {
            $from = $request->filled('from') ? $this->jalaliParts($request->get('from')) : null;
            $to = $request->filled('to') ? $this->jalaliParts($request->get('to')) : null;
            if ($from) {
                $q->where(function ($w) use ($from) {
                    $w->where('year', '>', $from[0])
                        ->orWhere(function ($w2) use ($from) {
                            $w2->where('year', $from[0])->where('month', '>', $from[1]);
                        })
                        ->orWhere(function ($w2) use ($from) {
                            $w2->where('year', $from[0])->where('month', $from[1])->where('day', '>=', $from[2]);
                        });
                });
            }
            if ($to) {
                $q->where(function ($w) use ($to) {
                    $w->where('year', '<', $to[0])
                        ->orWhere(function ($w2) use ($to) {
                            $w2->where('year', $to[0])->where('month', '<', $to[1]);
                        })
                        ->orWhere(function ($w2) use ($to) {
                            $w2->where('year', $to[0])->where('month', $to[1])->where('day', '<=', $to[2]);
                        });
                });
            }
            if (!$from && !$to) {
                // default: today (Jalali)
                $today = explode('/', jdate('Y/m/d', '', '', '', 'en'));
                $q->where([['year', (int) $today[0]], ['month', (int) $today[1]], ['day', (int) $today[2]]]);
            }
        }

        if ($request->get('confirmed') === '1' || $request->get('confirmed') === 1) {
            $q->where('is_confirmed', true);
        }
        if ($request->filled('status') && is_numeric($request->get('status'))) {
            $q->where('status', (int) $request->get('status'));
        }

        $limit = min(500, max(1, (int) ($request->get('limit') ?: 200)));
        $rows = $q->limit($limit)->get();

        return response()->json([
            'ok' => true,
            'provider' => 'kamilo',
            'doctor_id' => $ctx['doctor_id'],
            'count' => $rows->count(),
            'appointments' => $rows->map(function ($a) {
                return $this->formatAppointment($a);
            })->values(),
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Patient + rich appointment dossier by mobile (caller-ID / مشاهده پرونده).
     */
    public function byPhone(Request $request, $phone)
    {
        $ctx = $this->auth($request);
        $phone = $this->normalizePhone($phone);
        abort_unless(preg_match('/^09\d{9}$/', $phone), 422, 'شماره موبایل نامعتبر است');

        $user = User::where('mobile', $phone)->orderBy('id')->first();
        if (!$user) {
            return response()->json($this->emptyDossier($phone, $ctx['doctor_id']), 200, [], JSON_UNESCAPED_UNICODE);
        }

        return response()->json(
            $this->buildDossier($user, $ctx['doctor_id'], $phone),
            200,
            [],
            JSON_UNESCAPED_UNICODE
        );
    }

    /**
     * Same rich dossier by patient user id (scoped to token doctor when set).
     */
    public function dossier(Request $request, $id)
    {
        $ctx = $this->auth($request);
        abort_unless(ctype_digit((string) $id), 422, 'شناسه بیمار نامعتبر است');

        $user = User::find((int) $id);
        if (!$user) {
            return response()->json($this->emptyDossier('', $ctx['doctor_id'], (int) $id), 200, [], JSON_UNESCAPED_UNICODE);
        }

        $phone = $this->normalizePhone((string) $user->mobile);

        return response()->json(
            $this->buildDossier($user, $ctx['doctor_id'], $phone),
            200,
            [],
            JSON_UNESCAPED_UNICODE
        );
    }

    private function auth(Request $request): array
    {
        $token = (string) $request->bearerToken();
        if (!VoipIntegration::accepts($token)) {
            $on = VoipIntegration::settings()['enabled'];
            abort($on ? 401 : 403, $on ? 'توکن نامعتبر است' : 'ارتباط با VoIP در تنظیمات غیرفعال است');
        }

        return [
            'doctor_id' => VoipIntegration::doctorIdFromToken($token),
        ];
    }

    private function jalaliParts($s)
    {
        $s = strtr((string) $s, self::DIGITS);
        $s = str_replace('-', '/', trim($s));
        if (!preg_match('/^(\d{4})\/(\d{1,2})\/(\d{1,2})$/', $s, $m)) {
            return null;
        }

        return [(int) $m[1], (int) $m[2], (int) $m[3]];
    }

    private function normalizePhone($phone): string
    {
        $phone = preg_replace('/\D/', '', strtr((string) $phone, self::DIGITS));
        if (strpos($phone, '98') === 0 && strlen($phone) >= 12) {
            $phone = '0' . substr($phone, 2);
        }
        if (strpos($phone, '9') === 0 && strlen($phone) === 10) {
            $phone = '0' . $phone;
        }

        return $phone;
    }

    private function formatAppointment(AppointmentUser $a): array
    {
        $detail = is_array($a->detail) ? $a->detail : [];
        $user = $a->user;
        $timeFrom = substr((string) ($a->confirmed_time_from ?: $a->time_from), 0, 5);
        $service = $a->confirmed_service_name
            ?: ($detail[UserMeta::SERVICE_DETAIL] ?? ($detail[UserMeta::SERVICE] ?? ''));
        $jalali = sprintf('%04d/%02d/%02d', $a->year, $a->month, $a->day);
        $statusVal = (int) $a->status;

        return [
            'id' => $a->id,
            'code' => $a->code,
            'date' => $jalali,
            'jalali' => function_exists('jdate')
                ? jdate('l j F', jmktime(0, 0, 0, $a->month, $a->day, $a->year), '', '', 'en')
                : $jalali,
            'time' => $timeFrom,
            'time_from' => substr((string) $a->time_from, 0, 5),
            'time_to' => substr((string) $a->time_to, 0, 5),
            'service' => (string) $service,
            'part' => optional($a->appointmentPart)->title,
            'type' => (string) $a->type,
            'kind' => ((int) $a->kind === AppointmentUser::KIND_MAIN_APPOINTMENT) ? 'main' : 'subsidiary',
            'status' => $statusVal,
            'status_label' => self::STATUS[$statusVal] ?? (string) $statusVal,
            'is_confirmed' => (bool) $a->is_confirmed,
            'doctor' => [
                'id' => $a->doctor_id,
                'name' => trim((string) optional($a->doctor)->full_name),
            ],
            'patient' => [
                'id' => optional($user)->id,
                'name' => trim((string) optional($user)->full_name),
                'first_name' => (string) optional($user)->first_name,
                'last_name' => (string) optional($user)->last_name,
                'mobile' => (string) optional($user)->mobile,
                'national_code' => (string) optional($user)->national_code,
                'file_no' => (string) (optional($user)->medical_record_number ?: ($detail[AppointmentUser::MEDICAL_RECORD_NUMBER] ?? '')),
            ],
        ];
    }

    private function formatApptCrmRow(AppointmentUser $a): array
    {
        $rich = $this->formatDossierAppointment($a);

        return [
            'id' => $rich['id'],
            'date' => $rich['date'],
            'jalali' => $rich['date'],
            'time' => $rich['time'],
            'service' => $rich['service'],
            'place' => $rich['part'],
            'status' => $rich['status'],
            'status_label' => $rich['status_label'],
            'amount_paid' => $rich['amount_paid'],
        ];
    }

    private function emptyDossier(string $phone, $doctorId, $requestedId = null): array
    {
        return [
            'ok' => true,
            'found' => false,
            'provider' => 'kamilo',
            'id' => $requestedId,
            'name' => '',
            'phone' => $phone,
            'doctor_id' => $doctorId,
            'appointments_total' => 0,
            'appointments_received_count' => 0,
            'appointments_active_count' => 0,
            'appointments_cancelled_count' => 0,
            'appointments_in_person' => 0,
            'appointments_online' => 0,
            'next_appointment' => null,
            'last_received_appointment' => null,
            'summary' => null,
            'patient' => null,
            'appointments' => [],
            'in_person_appointments' => [],
            'online_appointments' => [],
            'dossier_notes' => null,
            'dossier_notes_supported' => false,
            'dossier_notes_note' => 'No patient-level dossier notes column/table in kamilo (only per-appointment confirmed_comment).',
            'field_gaps' => $this->fieldGaps(),
        ];
    }

    private function buildDossier(User $user, $doctorId, string $phone): array
    {
        $q = AppointmentUser::query()
            ->with(['appointmentPart', 'doctor', 'agent'])
            ->where('user_id', $user->id)
            ->orderByDesc('year')->orderByDesc('month')->orderByDesc('day')
            ->orderByDesc('id');
        if ($doctorId) {
            $q->where('doctor_id', $doctorId);
        }
        $rows = $q->limit(400)->get();

        $dossierAppts = $rows->map(function ($a) {
            return $this->formatDossierAppointment($a);
        })->values();

        $inPerson = [];
        $online = [];
        foreach ($rows as $a) {
            $item = $this->formatApptCrmRow($a);
            $type = strtoupper((string) $a->type);
            if (strpos($type, 'ONLINE') !== false || strpos($type, 'آنلاین') !== false) {
                $online[] = $item;
            } else {
                $inPerson[] = $item;
            }
        }

        $todayKey = $this->todayJalaliKey();
        $nextModel = null;
        $lastReceivedModel = null;
        foreach ($rows->sortBy(function ($a) {
            return sprintf('%04d%02d%02d%s', $a->year, $a->month, $a->day, substr((string) $a->time_from, 0, 5));
        }) as $a) {
            if ($this->isTerminalStatus((int) $a->status)) {
                continue;
            }
            $key = $this->apptJalaliKey($a);
            if ($key >= $todayKey) {
                $nextModel = $a;
                break;
            }
        }
        foreach ($rows as $a) {
            if (!$this->isReceivedStatus((int) $a->status)) {
                continue;
            }
            if ($this->apptJalaliKey($a) < $todayKey) {
                $lastReceivedModel = $a;
                break; // rows already newest→oldest
            }
        }

        $activeCount = $rows->filter(function ($a) {
            return !$this->isTerminalStatus((int) $a->status);
        })->count();
        $cancelledCount = $rows->filter(function ($a) {
            return (int) $a->status === AppointmentUser::STATUS_CANCELED;
        })->count();
        $receivedCount = $rows->filter(function ($a) {
            return $this->isReceivedStatus((int) $a->status);
        })->count();

        $focus = $nextModel ?: ($rows->first() ?: null);
        $address = trim((string) $user->address);
        [$city, $province] = $this->splitCityProvince($address);
        $secondMobile = $this->resolveSecondMobile($user, $focus);

        $name = trim((string) $user->full_name);
        $next = $nextModel ? $this->formatDossierAppointment($nextModel) : null;
        $lastReceived = $lastReceivedModel ? $this->formatDossierAppointment($lastReceivedModel) : null;

        $summary = null;
        if ($focus) {
            $f = $this->formatDossierAppointment($focus);
            $summary = [
                'full_name' => $name,
                'agent_name' => $f['agent_name'],
                'agent_id' => $f['agent_id'],
                'is_first_appointment' => $f['is_first_appointment'],
                'is_first_appointment_label' => $f['is_first_appointment'] ? 'بله' : 'خیر',
                'appointments_received_count' => $receivedCount,
                'mobile' => $phone ?: (string) $user->mobile,
                'second_mobile' => $secondMobile,
                'part' => $f['part'],
                'doctor' => $f['doctor'],
                'next_appointment_at' => $next ? ['date' => $next['date'], 'time' => $next['time'], 'id' => $next['id']] : null,
                'last_received_appointment_at' => $lastReceived
                    ? ['date' => $lastReceived['date'], 'time' => $lastReceived['time'], 'id' => $lastReceived['id']]
                    : null,
                'last_received_created_at' => $lastReceived['created_at'] ?? null,
                'appointment_kind' => $f['kind'],
                'appointment_kind_label' => $f['kind_label'],
                'status' => $f['status'],
                'status_label' => $f['status_label'],
                'is_confirmed' => $f['is_confirmed'],
                'is_confirmed_label' => $f['is_confirmed'] ? 'confirm شده' : 'confirm نشده',
                'city' => $city,
                'province' => $province,
                'address' => $address !== '' ? $address : null,
                'appointment_category' => $f['appointment_category'],
                'appointment_category_label' => $f['appointment_category_label'],
                'identifier_code' => $f['identifier_code'],
                'tracking_code' => $f['tracking_code'],
                'appointment_id' => $f['id'],
                'appointments_active_count' => $activeCount,
                'appointments_cancelled_count' => $cancelledCount,
                'dossier_notes' => null,
            ];
        }

        return [
            'ok' => true,
            'found' => true,
            'provider' => 'kamilo',
            'id' => $user->id,
            'name' => $name,
            'first_name' => (string) $user->first_name,
            'last_name' => (string) $user->last_name,
            'phone' => $phone ?: (string) $user->mobile,
            'national_code' => (string) $user->national_code,
            'file_no' => (string) $user->medical_record_number,
            'second_mobile' => $secondMobile,
            'city' => $city,
            'province' => $province,
            'address' => $address !== '' ? $address : null,
            'appointments_total' => $rows->count(),
            'appointments_received_count' => $receivedCount,
            'appointments_active_count' => $activeCount,
            'appointments_cancelled_count' => $cancelledCount,
            'appointments_in_person' => count($inPerson),
            'appointments_online' => count($online),
            'next_appointment' => $next,
            'last_received_appointment' => $lastReceived,
            'summary' => $summary,
            'patient' => [
                'id' => $user->id,
                'name' => $name,
                'first_name' => (string) $user->first_name,
                'last_name' => (string) $user->last_name,
                'mobile' => $phone ?: (string) $user->mobile,
                'second_mobile' => $secondMobile,
                'national_code' => (string) $user->national_code,
                'file_no' => (string) $user->medical_record_number,
                'city' => $city,
                'province' => $province,
                'address' => $address !== '' ? $address : null,
            ],
            'appointments' => $dossierAppts,
            'in_person_appointments' => $inPerson,
            'online_appointments' => $online,
            'dossier_notes' => null,
            'dossier_notes_supported' => false,
            'dossier_notes_note' => 'No patient-level dossier notes column/table in kamilo (only per-appointment confirmed_comment).',
            'field_gaps' => $this->fieldGaps(),
            'doctor_id' => $doctorId,
        ];
    }

    private function formatDossierAppointment(AppointmentUser $a): array
    {
        $detail = is_array($a->detail) ? $a->detail : [];
        $service = $a->confirmed_service_name
            ?: ($detail[UserMeta::SERVICE_DETAIL] ?? ($detail[UserMeta::SERVICE] ?? optional($a->appointmentPart)->title));
        $jalali = sprintf('%04d/%02d/%02d', $a->year, $a->month, $a->day);
        $statusVal = (int) $a->status;
        // kind is cast to bool on the model; (int) true === KIND_MAIN_APPOINTMENT (1)
        $kindVal = ((int) $a->kind === AppointmentUser::KIND_MAIN_APPOINTMENT)
            ? AppointmentUser::KIND_MAIN_APPOINTMENT
            : AppointmentUser::KIND_SUBSIDIARY_APPOINTMENT;
        $catRaw = $detail['APPOINTMENT_FIRST_TYPE'] ?? null;
        $cat = ($catRaw !== null && $catRaw !== '' && is_numeric($catRaw)) ? (int) $catRaw : null;
        $identifier = isset($detail[AppointmentUser::IDENTIFIERـCODE])
            ? trim((string) $detail[AppointmentUser::IDENTIFIERـCODE])
            : '';
        $second = isset($detail[AppointmentUser::SECOND_MOBILE])
            ? trim((string) $detail[AppointmentUser::SECOND_MOBILE])
            : '';
        $agent = $a->relationLoaded('agent') ? $a->agent : $a->agent()->first();
        $doctor = $a->relationLoaded('doctor') ? $a->doctor : $a->doctor()->first();
        $time = substr((string) ($a->confirmed_time_from ?: $a->time_from), 0, 5);
        $createdAt = $a->created_at ? (string) $a->created_at : null;

        $statusLabel = self::STATUS[$statusVal] ?? (string) $statusVal;
        if ($statusVal === AppointmentUser::STATUS_CANCELED) {
            $kindStatusLabel = 'کنسل شده';
        } elseif ($kindVal === AppointmentUser::KIND_SUBSIDIARY_APPOINTMENT) {
            $kindStatusLabel = 'بین مریض';
        } else {
            $kindStatusLabel = 'اصلی';
        }

        return [
            'id' => $a->id,
            'tracking_code' => (string) $a->code,
            'code' => (string) $a->code,
            'date' => $jalali,
            'time' => $time,
            'time_from' => substr((string) $a->time_from, 0, 5),
            'time_to' => substr((string) $a->time_to, 0, 5),
            'service' => (string) $service,
            'part' => (string) optional($a->appointmentPart)->title,
            'type' => (string) $a->type,
            'kind' => $kindVal === AppointmentUser::KIND_MAIN_APPOINTMENT ? 'main' : 'subsidiary',
            'kind_label' => self::KIND[$kindVal] ?? (string) $kindVal,
            'kind_status_label' => $kindStatusLabel,
            'status' => $statusVal,
            'status_label' => $statusLabel,
            'is_confirmed' => (bool) $a->is_confirmed,
            'is_confirmed_label' => $a->is_confirmed ? 'confirm شده' : 'confirm نشده',
            'appointment_category' => $cat,
            'appointment_category_label' => $cat !== null ? (self::CATEGORY[$cat] ?? (string) $cat) : null,
            'is_first_appointment' => $cat === 1,
            'identifier_code' => $identifier !== '' ? $identifier : null,
            'second_mobile' => $second !== '' ? $second : null,
            'agent_id' => $a->agent_id ? (int) $a->agent_id : null,
            'agent_name' => $agent ? trim((string) $agent->full_name) : null,
            'doctor' => [
                'id' => $a->doctor_id ? (int) $a->doctor_id : null,
                'name' => $doctor ? trim((string) $doctor->full_name) : null,
            ],
            'visit' => (int) $a->visit,
            'visit_label' => AppointmentUser::$visitStatus[(int) $a->visit]['title'] ?? (string) $a->visit,
            'amount_paid' => $a->cost,
            'confirmed_comment' => $a->confirmed_comment !== null && $a->confirmed_comment !== ''
                ? (string) $a->confirmed_comment
                : null,
            'created_at' => $createdAt,
            'updated_at' => $a->updated_at ? (string) $a->updated_at : null,
        ];
    }

    private function resolveSecondMobile(User $user, $focusAppt): ?string
    {
        if ($focusAppt) {
            $detail = is_array($focusAppt->detail) ? $focusAppt->detail : [];
            $fromDetail = isset($detail[AppointmentUser::SECOND_MOBILE])
                ? trim((string) $detail[AppointmentUser::SECOND_MOBILE])
                : '';
            if ($fromDetail !== '') {
                return $fromDetail;
            }
        }
        $fromUser = trim((string) $user->second_mobile);

        return $fromUser !== '' ? $fromUser : null;
    }

    private function splitCityProvince(string $address): array
    {
        $address = trim($address);
        if ($address === '') {
            return [null, null];
        }
        foreach ([' / ', '/', ' - ', '،', ','] as $sep) {
            if (strpos($address, $sep) !== false) {
                $parts = array_map('trim', explode($sep, $address, 2));

                return [
                    $parts[0] !== '' ? $parts[0] : null,
                    isset($parts[1]) && $parts[1] !== '' ? $parts[1] : null,
                ];
            }
        }

        // ADDRESS meta is usually city-only; no separate province column in DB.
        return [$address, null];
    }

    private function fieldGaps(): array
    {
        return [
            'province' => 'No dedicated province column; user_metas.ADDRESS is city-like text (sometimes "city/province").',
            'dossier_notes' => 'No patient dossier notes table/column; stub only. Per-appointment notes live in appointment_users.confirmed_comment.',
            'city' => 'Mapped from user_metas.ADDRESS (meta_key=ADDRESS), not a column on users.',
        ];
    }

    private function todayJalaliKey(): int
    {
        return (int) jdate('Y', '', '', '', 'en') * 10000
            + (int) jdate('m', '', '', '', 'en') * 100
            + (int) jdate('d', '', '', '', 'en');
    }

    private function apptJalaliKey(AppointmentUser $a): int
    {
        return (int) $a->year * 10000 + (int) $a->month * 100 + (int) $a->day;
    }

    private function isTerminalStatus(int $status): bool
    {
        return in_array($status, [
            AppointmentUser::STATUS_CANCELED,
            AppointmentUser::STATUS_DELETED,
        ], true);
    }

    /** «نوبت دریافتی»: kept/received — not cancelled or deleted. */
    private function isReceivedStatus(int $status): bool
    {
        return !$this->isTerminalStatus($status);
    }
}

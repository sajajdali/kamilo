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

    public function me(Request $request)
    {
        $ctx = $this->auth($request);
        $doctor = $ctx['doctor_id'] ? User::find($ctx['doctor_id']) : null;

        return response()->json([
            'ok' => true,
            'provider' => 'kamilo',
            'doctor_id' => $ctx['doctor_id'],
            'doctor_name' => $doctor ? trim((string) $doctor->full_name) : null,
            'scope' => $ctx['doctor_id'] ? 'doctor' : 'all',
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
     * Patient + appointment history by mobile (caller-ID card shape for VoIP CRM).
     */
    public function byPhone(Request $request, $phone)
    {
        $ctx = $this->auth($request);
        $phone = $this->normalizePhone($phone);
        abort_unless(preg_match('/^09\d{9}$/', $phone), 422, 'شماره موبایل نامعتبر است');

        $user = User::where('mobile', $phone)->orderBy('id')->first();
        if (!$user) {
            // also try without leading 0 variants already normalized
            return response()->json([
                'found' => false,
                'phone' => $phone,
                'name' => '',
                'appointments_total' => 0,
                'in_person_appointments' => [],
                'online_appointments' => [],
            ], 200, [], JSON_UNESCAPED_UNICODE);
        }

        $q = AppointmentUser::query()
            ->with(['appointmentPart', 'doctor'])
            ->where('user_id', $user->id)
            ->orderByDesc('year')->orderByDesc('month')->orderByDesc('day')
            ->orderByDesc('id');
        if ($ctx['doctor_id']) {
            $q->where('doctor_id', $ctx['doctor_id']);
        }
        $rows = $q->limit(400)->get();

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

        $next = null;
        $todayKey = (int) jdate('Y', '', '', '', 'en') * 10000 + (int) jdate('m', '', '', '', 'en') * 100 + (int) jdate('d', '', '', '', 'en');
        foreach ($rows->sortBy(function ($a) {
            return sprintf('%04d%02d%02d%s', $a->year, $a->month, $a->day, substr((string) $a->time_from, 0, 5));
        }) as $a) {
            if ((int) $a->status === AppointmentUser::STATUS_CANCELED) {
                continue;
            }
            $key = (int) $a->year * 10000 + (int) $a->month * 100 + (int) $a->day;
            if ($key >= $todayKey) {
                $next = $this->formatApptCrmRow($a);
                break;
            }
        }

        $name = trim((string) $user->full_name);

        return response()->json([
            'found' => true,
            'id' => $user->id,
            'name' => $name,
            'first_name' => (string) $user->first_name,
            'last_name' => (string) $user->last_name,
            'phone' => $phone,
            'national_code' => (string) $user->national_code,
            'file_no' => (string) $user->medical_record_number,
            'appointments_total' => $rows->count(),
            'appointments_in_person' => count($inPerson),
            'appointments_online' => count($online),
            'next_appointment' => $next,
            'in_person_appointments' => $inPerson,
            'online_appointments' => $online,
            'doctor_id' => $ctx['doctor_id'],
        ], 200, [], JSON_UNESCAPED_UNICODE);
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
        $detail = is_array($a->detail) ? $a->detail : [];
        $service = $a->confirmed_service_name
            ?: ($detail[UserMeta::SERVICE_DETAIL] ?? ($detail[UserMeta::SERVICE] ?? optional($a->appointmentPart)->title));
        $jalali = sprintf('%04d/%02d/%02d', $a->year, $a->month, $a->day);
        $statusVal = (int) $a->status;

        return [
            'id' => $a->id,
            'date' => $jalali,
            'jalali' => $jalali,
            'time' => substr((string) ($a->confirmed_time_from ?: $a->time_from), 0, 5),
            'service' => (string) $service,
            'place' => (string) optional($a->appointmentPart)->title,
            'status' => $statusVal,
            'status_label' => self::STATUS[$statusVal] ?? (string) $statusVal,
            'amount_paid' => $a->cost,
        ];
    }
}

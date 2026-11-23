<?php

namespace Modules\Appointment\Http\Controllers\Admin;

use App\Base\AdminApiController;
use App\AdminActivityLog;
use App\Support\AdminActivityLogger;
use App\User;
use App\UserMeta;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Modules\Appointment\Entities\Appointment;
use Modules\Appointment\Entities\AppointmentPart;
use Modules\Appointment\Entities\AppointmentUser;
use Modules\Appointment\Exports\ConfirmedAppointmentUserExport;
use Modules\Appointment\Exports\AppointmentUserExport;
use Modules\Finance\Entities\Transaction;

class AppointmentUserController extends AdminApiController
{
    protected $routeBaseName = 'admin.appointment_user.';

    public function auditReport(Request $request)
    {
        $appointment = null;
        $deletionLog = null;
        $activityLogs = collect();
        $unlocked = (bool) $request->session()->get('appointment_audit_report_unlocked', false);

        if ($unlocked && $request->filled('appointment_id')) {
            $this->validate($request, [
                'appointment_id' => ['required', 'integer', 'min:1'],
            ]);

            $appointment = AppointmentUser::withTrashed()
                ->with(['user', 'agent', 'doctor', 'appointmentPart', 'confirmedBy', 'transaction', 'identifierHistories.actor'])
                ->find($request->input('appointment_id'));

            if ($appointment) {
                $activityLogs = AdminActivityLog::with('admin')
                    ->where('subject_id', $appointment->id)
                    ->where(function ($query) {
                        $query->where('module', 'appointment')
                            ->orWhere('subject_type', 'like', '%AppointmentUser%');
                    })
                    ->orderBy('id', 'desc')
                    ->get();

                $deletionLog = $activityLogs->first(function ($log) {
                    return strpos($log->action, 'deleted') !== false;
                });
            }
        }

        return view('appointment::admin.appointment_user.audit_report', compact('appointment', 'deletionLog', 'activityLogs', 'unlocked'));
    }

    public function unlockAuditReport(Request $request)
    {
        $this->validate($request, [
            'password' => ['required', 'digits:5'],
        ], [
            'password.required' => 'کد ورود را وارد کنید.',
            'password.digits' => 'کد ورود باید ۵ رقم باشد.',
        ]);

        if (!hash_equals('16022', (string) $request->input('password'))) {
            return response()->json([
                'ok' => false,
                'message' => 'کد واردشده صحیح نیست.',
            ], 422);
        }

        $request->session()->put('appointment_audit_report_unlocked', true);

        return response()->json([
            'ok' => true,
            'message' => 'دسترسی با موفقیت تأیید شد.',
        ]);
    }

    function apiIndex(Request $request)
    {
        // TODO: Implement apiIndex() method.
    }

    public function updateIdentifierCode(Request $request, AppointmentUser $appointmentUser)
    {
        if (!$this->canEditAppointmentUserDetails()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $oldValues = AdminActivityLogger::modelValues($appointmentUser);
        $validated = $request->validate([
            'identifier_code' => ['nullable', 'string', 'max:50'],
        ]);

        // فرض: کد معرف داخل detail ذخیره می‌شود
        // کلیدش هم همون IDENTIFIERـCODE هست که تو ویو استفاده کردید
        $key = AppointmentUser::IDENTIFIERـCODE; // همون کانستنت خودتون

        $detail = $appointmentUser->detail ?? [];
        $detail[$key] = $validated['identifier_code'] ?? null;

        $appointmentUser->detail = $detail;
        $appointmentUser->save();
        AdminActivityLogger::log('appointment.identifier_code.updated', $appointmentUser, $oldValues, AdminActivityLogger::modelValues($appointmentUser), [
            'module' => 'appointment',
            'description' => 'کد معرف نوبت تغییر کرد',
            'request_payload' => $request->except(['_token']),
        ]);

        return response()->json([
            'ok' => true,
            'identifier_code' => $detail[$key],
            'message' => 'کد معرف با موفقیت ذخیره شد.',
        ]);
    }

    public function updateDetails(Request $request, AppointmentUser $appointmentUser)
    {
        if (!$this->canEditAppointmentUserDetails()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $oldValues = AdminActivityLogger::modelValues($appointmentUser);
        $validated = $request->validate([
            'service_detail' => ['nullable', 'string', 'max:255'],
            'identifier_code' => ['nullable', 'string', 'max:50'],
            'medical_record_number' => ['nullable', 'string', 'max:50'],
            'second_mobile' => ['nullable', 'string', 'max:30'],
            'service' => ['nullable', 'string', 'max:1000'],
            'related_parts' => ['nullable', 'array'],
            'related_parts.*' => ['nullable', 'string', 'max:20'],
        ]);

        $detail = $appointmentUser->detail ?? [];
        $detail = is_array($detail) ? $detail : [];

        $this->setDetailValue($detail, UserMeta::SERVICE_DETAIL, $validated['service_detail'] ?? null);
        $this->setDetailValue($detail, AppointmentUser::IDENTIFIERـCODE, $validated['identifier_code'] ?? null);
        $this->setDetailValue($detail, AppointmentUser::SECOND_MOBILE, $validated['second_mobile'] ?? null);
        $this->setDetailValue($detail, UserMeta::SERVICE, $validated['service'] ?? null);

        $medicalRecordNumber = trim((string) ($validated['medical_record_number'] ?? ''));
        $savedMedicalRecordNumber = $appointmentUser->user
            ? trim((string) $appointmentUser->user->medical_record_number)
            : '';
        $effectiveMedicalRecordNumber = $medicalRecordNumber !== ''
            ? $medicalRecordNumber
            : $savedMedicalRecordNumber;
        $this->setDetailValue($detail, AppointmentUser::MEDICAL_RECORD_NUMBER, $effectiveMedicalRecordNumber);

        $appointmentUser->detail = $detail;
        $appointmentUser->r_part = $this->cleanRelatedParts($request->input('related_parts', []));
        $appointmentUser->save();

        if ($medicalRecordNumber !== '' && $appointmentUser->user_id) {
            UserMeta::updateOrCreate(
                ['user_id' => $appointmentUser->user_id, 'meta_key' => UserMeta::MEDICAL_RECORD_NUMBER],
                ['meta_value' => $medicalRecordNumber]
            );
        }

        AdminActivityLogger::log('appointment.details.updated', $appointmentUser, $oldValues, AdminActivityLogger::modelValues($appointmentUser), [
            'module' => 'appointment',
            'description' => 'جزئیات نوبت از گزارش نوبت‌ها ویرایش شد',
            'request_payload' => $request->except(['_token']),
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'جزئیات نوبت با موفقیت ذخیره شد.',
            'data' => [
                'service_detail' => $detail[UserMeta::SERVICE_DETAIL] ?? '',
                'identifier_code' => $detail[AppointmentUser::IDENTIFIERـCODE] ?? '',
                'medical_record_number' => $effectiveMedicalRecordNumber,
                'second_mobile' => $detail[AppointmentUser::SECOND_MOBILE] ?? '',
                'service' => $detail[UserMeta::SERVICE] ?? '',
                'related_parts' => $appointmentUser->r_part ?? [],
            ],
        ], Response::HTTP_OK, [], JSON_UNESCAPED_UNICODE);
    }

    private function canEditAppointmentUserDetails()
    {
        $user = Auth::user();

        return $user && (
            $user->hasRole('SUPER_ADMIN') ||
            $user->can('SUPERADMIN_ACCESS') ||
            $user->can('edit_admin_appointment_user_details')
        );
    }

    private function isSuperAdmin()
    {
        $user = Auth::user();

        return $user && ($user->hasRole('SUPER_ADMIN') || $user->can('SUPERADMIN_ACCESS'));
    }

    private function setDetailValue(array &$detail, $key, $value)
    {
        $value = is_string($value) ? trim($value) : $value;
        if ($value === null || $value === '') {
            unset($detail[$key]);
            return;
        }

        $detail[$key] = $value;
    }

    private function cleanRelatedParts($relatedParts)
    {
        if (!is_array($relatedParts)) {
            return [];
        }

        $clean = [];
        foreach ($relatedParts as $partName => $partTime) {
            $partName = trim((string) $partName);
            $partTime = trim((string) $partTime);
            if ($partName === '' || $partTime === '') {
                continue;
            }
            $clean[$partName] = $partTime;
        }

        return $clean;
    }

    private function identifierCodeSearchTerm(Request $request)
    {
        return trim((string) ($request->input('identifier_code') ?: $request->input('identifierـcode')));
    }

    private function defaultJalaliDate($days = 0)
    {
        if (intval($days) === 0) {
            return jdate('Y/m/d', '', '', '', 'en');
        }

        return jdate('Y/m/d', strtotime('+' . intval($days) . ' days'), '', '', 'en');
    }

    private function selectedDate(Request $request, $defaultDays = 0)
    {
        $date = trim((string) $request->input('date'));

        return $date !== '' ? $this->convertNumber($date) : $this->defaultJalaliDate($defaultDays);
    }

    private function selectedDateParts($date)
    {
        $parts = explode('/', $this->convertNumber($date));

        return [
            'year' => intval($parts[0] ?? 0),
            'month' => intval($parts[1] ?? 0),
            'day' => intval($parts[2] ?? 0),
        ];
    }

    private function appointmentUserBaseQuery()
    {
        return AppointmentUser::withTrashed(Auth::user()->viewTrasheed())
            ->with(['user', 'agent', 'doctor', 'appointmentPart', 'confirmedBy'])
            ->where(function ($query) {
                if (!Auth::user()->can('admin_type_full_appointment_user_access')) {
                    $doctorRolsid = AppointmentPart::DOCTOR_ROLES;
                    if (in_array(auth()->user()->roles->first()->id, $doctorRolsid)) {
                        $query->whereDoctorId(Auth::id());
                    } else {
                        $query->whereAgentId(Auth::id());
                    }
                }
            });
    }

    private function appointmentUsersForDate($date)
    {
        $parts = $this->selectedDateParts($date);

        return $this->appointmentUserBaseQuery()
            ->where([
                ['year', $parts['year']],
                ['month', $parts['month']],
                ['day', $parts['day']],
            ]);
    }

    private function applyIdentifierCodeFilter($query, $identifierCode)
    {
        if ($identifierCode === '') {
            return;
        }

        $query->whereRaw(
            "JSON_VALID(detail) AND CONVERT(JSON_UNQUOTE(JSON_EXTRACT(detail, '$.\"".AppointmentUser::IDENTIFIERـCODE."\"')) USING utf8) COLLATE utf8_general_ci LIKE CONVERT(? USING utf8) COLLATE utf8_general_ci",
            ['%' . $identifierCode . '%']
        );
    }

    private function applyConfirmedPatientFilters($query, Request $request)
    {
        $patientName = trim((string) $request->input('patient_name'));
        if ($patientName !== '') {
            $terms = preg_split('/\s+/u', $patientName, -1, PREG_SPLIT_NO_EMPTY);
            $query->whereHas('user', function ($userQuery) use ($terms) {
                foreach ($terms as $term) {
                    $userQuery->whereHas('userMetas', function ($metaQuery) use ($term) {
                        $metaQuery->whereIn('meta_key', [UserMeta::FIRST_NAME, UserMeta::LAST_NAME])
                            ->where('meta_value', 'like', '%' . $term . '%');
                    });
                }
            });
        }

        $mobile = preg_replace('/\D+/u', '', $this->convertNumber((string) $request->input('mobile')));
        if ($mobile !== '') {
            $query->where(function ($mobileQuery) use ($mobile) {
                $mobileQuery->whereHas('user', function ($userQuery) use ($mobile) {
                    $userQuery->where('mobile', 'like', '%' . $mobile . '%')
                        ->orWhereHas('userMetas', function ($metaQuery) use ($mobile) {
                            $metaQuery->where('meta_key', UserMeta::SECOND_MOBILE)
                                ->where('meta_value', 'like', '%' . $mobile . '%');
                        });
                })->orWhereRaw(
                    "JSON_VALID(detail) AND JSON_UNQUOTE(JSON_EXTRACT(detail, '$.\"" . AppointmentUser::SECOND_MOBILE . "\"')) LIKE ?",
                    ['%' . $mobile . '%']
                );
            });
        }

        $nationalCode = preg_replace('/\D+/u', '', $this->convertNumber((string) $request->input('national_code')));
        if ($nationalCode !== '') {
            $query->whereHas('user.userMetas', function ($metaQuery) use ($nationalCode) {
                $metaQuery->where('meta_key', UserMeta::NATIONAL_CODE)
                    ->where('meta_value', 'like', '%' . $nationalCode . '%');
            });
        }

        $userId = intval($this->convertNumber((string) $request->input('user_id')));
        if ($userId > 0) {
            $query->where('user_id', $userId);
        }

        $appointmentId = intval($this->convertNumber((string) $request->input('appointment_id')));
        if ($appointmentId > 0) {
            $query->whereKey($appointmentId);
        }

        $doctorId = intval($this->convertNumber((string) $request->input('doctor_id')));
        if ($doctorId > 0) {
            $query->where('doctor_id', $doctorId);
        }

        $appointmentPartId = intval($this->convertNumber((string) $request->input('appointment_part_id')));
        if ($appointmentPartId > 0) {
            $query->whereIn('appointment_part_id', $this->appointmentPartAndDescendantIds($appointmentPartId));
        }

        $this->applyIdentifierCodeFilter($query, $this->identifierCodeSearchTerm($request));
    }

    private function appointmentPartAndDescendantIds($appointmentPartId)
    {
        $ids = [intval($appointmentPartId)];
        $pendingIds = $ids;

        while (!empty($pendingIds)) {
            $childIds = AppointmentPart::whereIn('parent_id', $pendingIds)->pluck('id')->map(function ($id) {
                return intval($id);
            })->all();
            $childIds = array_values(array_diff($childIds, $ids));
            if (empty($childIds)) {
                break;
            }

            $ids = array_merge($ids, $childIds);
            $pendingIds = $childIds;
        }

        return array_values(array_unique($ids));
    }

    private function confirmedPartFilterOptions()
    {
        $partsByParent = AppointmentPart::orderBy('title')->get()->groupBy(function ($part) {
            return intval($part->parent_id);
        });
        $options = [];
        $appendChildren = function ($parentId, $depth) use (&$appendChildren, &$options, $partsByParent) {
            foreach ($partsByParent->get(intval($parentId), collect()) as $part) {
                $options[] = [
                    'id' => $part->id,
                    'title' => str_repeat('— ', $depth) . $part->title,
                ];
                $appendChildren($part->id, $depth + 1);
            }
        };

        $appendChildren(0, 0);

        return $options;
    }

    public function confirmedIndex(Request $request)
    {
        return $this->confirmedList($request, 1, 'لیست confirm شده‌ها', 'پیش‌فرض این لیست تاریخ فردا است؛ در صورت نیاز تاریخ دیگری انتخاب کنید.', false);
    }

    public function confirmedDailyIndex(Request $request)
    {
        return $this->confirmedList($request, 0, 'فایل نوبت‌های روز', 'تاریخ مدنظر را انتخاب کنید و ثبت بزنید تا دفعات بعد همین تاریخ از منو باز شود.', true);
    }

    private function confirmedList(Request $request, $defaultDays, $title, $description, $canSaveSelectedDate)
    {
        $selectedDate = $this->confirmedListSelectedDate($request, $defaultDays, $canSaveSelectedDate);
        $confirmedDoctors = User::whereHas('roles', function ($roleQuery) {
            $roleQuery->whereIn('roles.id', AppointmentPart::DOCTOR_ROLES);
        })->get()->sortBy('full_name')->values();
        $confirmedPartOptions = $this->confirmedPartFilterOptions();
        $query = $this->appointmentUsersForDate($selectedDate)
            ->where('is_confirmed', true)
            ->orderByRaw('COALESCE(confirmed_time_from, time_from)')
            ->orderBy('id');

        $this->applyConfirmedPatientFilters($query, $request);

        if ($request->export != null && $request->export == "1") {
            $appointments = $query->get();
            $exportTitle = $title . " برای " . $selectedDate;

            return new ConfirmedAppointmentUserExport($appointments, $exportTitle);
        }

        $mAppointmentUser = $query
            ->paginate(50)
            ->appends($request->query());

        return view('appointment::admin.appointment_user.confirm', compact(
            'mAppointmentUser',
            'selectedDate',
            'title',
            'description',
            'canSaveSelectedDate',
            'confirmedDoctors',
            'confirmedPartOptions'
        ));
    }

    private function confirmedListSelectedDate(Request $request, $defaultDays, $canSaveSelectedDate)
    {
        if (!$canSaveSelectedDate) {
            return $this->selectedDate($request, $defaultDays);
        }

        $sessionKey = 'appointment_confirmed_daily_selected_date';
        $date = trim((string) $request->input('date'));
        $selectedDate = $date !== ''
            ? $this->convertNumber($date)
            : $request->session()->get($sessionKey, $this->defaultJalaliDate($defaultDays));

        if (intval($request->input('save_date')) === 1) {
            $request->session()->put($sessionKey, $selectedDate);
            $request->session()->flash('successful', 'تاریخ فایل نوبت‌های روز با موفقیت ثبت شد.');
        }

        return $selectedDate;
    }

    public function updateConfirm(Request $request, AppointmentUser $appointmentUser)
    {
        if (!Auth::user()->can('confirm_admin_appointment_user')) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $oldValues = AdminActivityLogger::modelValues($appointmentUser);
        $isConfirmed = intval($request->input('is_confirmed')) === 1;

        $validated = $request->validate([
            'confirmed_time_from' => [$isConfirmed ? 'required' : 'nullable', 'regex:/^([01][0-9]|2[0-3]):[0-5][0-9]$/'],
            'send_confirmation_sms' => ['nullable', 'in:0,1'],
            'send_sms_to_primary_mobile' => ['nullable', 'in:0,1'],
            'send_sms_to_second_mobile' => ['nullable', 'in:0,1'],
        ]);

        $confirmedTimeFrom = $this->emptyToNull($validated['confirmed_time_from'] ?? null);
        $originalTimeFrom = substr($appointmentUser->time_from, 0, 5);

        $appointmentUser->is_confirmed = $isConfirmed;
        $appointmentUser->confirmed_by = $isConfirmed ? Auth::id() : null;
        $appointmentUser->confirmed_at = $isConfirmed ? Carbon::now() : null;
        $appointmentUser->confirmed_time_from = $isConfirmed && $confirmedTimeFrom !== $originalTimeFrom
            ? $confirmedTimeFrom
            : null;
        $appointmentUser->save();

        $smsSent = false;
        if ($isConfirmed && intval($request->input('send_confirmation_sms')) === 1) {
            $recipients = $this->confirmationSmsRecipients($request, $appointmentUser);

            if (empty($recipients)) {
                $request->session()->flash('sys_error', 'نوبت Confirm شد، اما برای ارسال پیامک حداقل یک شماره باید انتخاب شود.');
            } else {
                $this->sendConfirmationSms($appointmentUser, $confirmedTimeFrom, $recipients);
                $smsSent = true;
            }
        }

        AdminActivityLogger::log('appointment.confirm.' . ($isConfirmed ? 'confirmed' : 'removed'), $appointmentUser, $oldValues, AdminActivityLogger::modelValues($appointmentUser), [
            'module' => 'appointment',
            'description' => $isConfirmed ? 'نوبت به عنوان confirm شده ثبت شد' : 'confirm نوبت برداشته شد',
            'request_payload' => $request->except(['_token']),
        ]);

        $successMessage = $isConfirmed ? 'نوبت با موفقیت Confirm شد.' : 'Confirm نوبت برداشته شد.';
        if ($smsSent) {
            $successMessage .= ' پیامک ساعت نوبت نیز ارسال شد.';
        }
        $request->session()->flash('successful', $successMessage);

        return redirect()->back();
    }

    private function confirmationSmsRecipients(Request $request, AppointmentUser $appointmentUser): array
    {
        $detail = is_array($appointmentUser->detail) ? $appointmentUser->detail : [];
        $recipients = [];

        if ($request->input('send_sms_to_primary_mobile') === '1') {
            $recipients[] = optional($appointmentUser->user)->mobile;
        }
        if ($request->input('send_sms_to_second_mobile') === '1') {
            $recipients[] = $detail[AppointmentUser::SECOND_MOBILE]
                ?? optional($appointmentUser->user)->second_mobile;
        }

        return array_values(array_unique(array_filter(array_map('trim', $recipients))));
    }

    private function sendConfirmationSms(AppointmentUser $appointmentUser, string $timeFrom, array $recipients): void
    {
        $template = 'kamiloReminder12';
        $dateAppointment = "{$appointmentUser->year}/{$appointmentUser->month}/{$appointmentUser->day}";
        $dayName = jdate('l', jmktime(0, 0, 0, $appointmentUser->month, $appointmentUser->day, $appointmentUser->year));
        $detail = is_array($appointmentUser->detail) ? $appointmentUser->detail : [];
        $serviceName = $appointmentUser->confirmed_service_name
            ?: ($detail[UserMeta::SERVICE_DETAIL] ?? ($detail[UserMeta::SERVICE] ?? optional($appointmentUser->appointmentPart)->title));
        $link = $detail[AppointmentUser::SHORT_LINK] ?? '';

        foreach ($recipients as $recipient) {
            smsNow(
                $recipient,
                $template,
                optional($appointmentUser->user)->first_name,
                $dayName,
                $dateAppointment,
                $timeFrom,
                $serviceName,
                optional($appointmentUser->doctor)->full_name,
                $link
            );
        }

        $detail['CONFIRM_SMS'] = [
            'template' => $template,
            'time_from' => $timeFrom,
            'recipients' => $recipients,
            'admin_id' => Auth::id(),
            'send_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];
        $appointmentUser->detail = $detail;
        $appointmentUser->save();
    }

    public function updateConfirmedListDetails(Request $request, AppointmentUser $appointmentUser)
    {
        if (!Auth::user()->can('confirm_admin_appointment_user')) {
            abort(Response::HTTP_FORBIDDEN);
        }

        if (!$appointmentUser->is_confirmed) {
            $request->session()->flash('sys_error', 'فقط نوبت‌های confirm شده در این لیست قابل ویرایش هستند.');
            return redirect()->back();
        }

        $validated = $request->validate([
            'confirmed_time_from' => ['nullable', 'regex:/^([01][0-9]|2[0-3]):[0-5][0-9]$/'],
            'confirmed_service_name' => ['nullable', 'string', 'max:255'],
            'confirmed_comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $oldValues = AdminActivityLogger::modelValues($appointmentUser);
        $confirmedTimeFrom = $this->emptyToNull($validated['confirmed_time_from'] ?? null);
        $originalTimeFrom = substr($appointmentUser->time_from, 0, 5);
        $detail = is_array($appointmentUser->detail) ? $appointmentUser->detail : [];
        $originalServiceName = $detail[UserMeta::SERVICE_DETAIL] ?? ($detail[UserMeta::SERVICE] ?? '');
        $confirmedServiceName = $this->emptyToNull($validated['confirmed_service_name'] ?? null);

        $appointmentUser->confirmed_time_from = $confirmedTimeFrom === $originalTimeFrom ? null : $confirmedTimeFrom;
        $appointmentUser->confirmed_service_name = $confirmedServiceName === $originalServiceName ? null : $confirmedServiceName;
        $appointmentUser->confirmed_comment = $this->emptyToNull($validated['confirmed_comment'] ?? null);
        $appointmentUser->save();

        AdminActivityLogger::log('appointment.confirmed_list_details.updated', $appointmentUser, $oldValues, AdminActivityLogger::modelValues($appointmentUser), [
            'module' => 'appointment',
            'description' => 'جزئیات نمایشی لیست confirm شده‌ها ویرایش شد',
            'request_payload' => $request->except(['_token']),
        ]);

        $request->session()->flash('successful', 'جزئیات لیست confirm شده‌ها ذخیره شد.');

        return redirect()->back();
    }

    private function emptyToNull($value)
    {
        $value = is_string($value) ? trim($value) : $value;

        return $value === '' ? null : $value;
    }

    /**
     * Apply an inclusive filter to the appointment's Jalali date columns.
     *
     * Comparing year, month and day independently breaks ranges that cross a
     * month or year boundary (for example 1405/06/25 through 1405/07/25).
     */
    private function applyAppointmentDateRange($query, $startDate, $endDate)
    {
        $dateExpression = '((year * 10000) + (month * 100) + day)';

        $startDateKey = $this->jalaliDateKey($startDate);
        if ($startDateKey !== null) {
            $query->whereRaw($dateExpression . ' >= ?', [$startDateKey]);
        }

        $endDateKey = $this->jalaliDateKey($endDate);
        if ($endDateKey !== null) {
            $query->whereRaw($dateExpression . ' <= ?', [$endDateKey]);
        }
    }

    private function jalaliDateKey($date)
    {
        if (!is_string($date) || !preg_match('/^(\d{4})\/(\d{1,2})\/(\d{1,2})$/', trim($date), $parts)) {
            return null;
        }

        return ((int) $parts[1] * 10000) + ((int) $parts[2] * 100) + (int) $parts[3];
    }

    /**
     * Web Index
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|AppointmentUserExport
     */
    function webIndex(Request $request)
    {
        $parts = AppointmentPart::whereParentId(0)->get();
        $doctors = User::role(AppointmentPart::DOCTOR_ROLES)->get();
        if ($request->export != null && $request->export == "1") {
            if ($request->act != null && $request->act == "search") {

                $mAppointmentUser = AppointmentUser::withTrashed()->where(function ($query) use ($request) {
                    /** @var AppointmentUser $query */
                    if (!Auth::user()->can('admin_type_full_appointment_user_access')) {
                        $doctorRolsid = AppointmentPart::DOCTOR_ROLES;
                        if (in_array(auth()->user()->roles->first()->id, $doctorRolsid)) {
                            $query->whereDoctorId(Auth::id());
                        } else {
                            $query->whereAgentId(Auth::id());
                        }
                    }
                    if (intval($request->user_id) != 0) {
                        $query->whereUserId($request->user_id);
                    }

                    if ($request->start_date_store_appointment != null) {

                        $from = explode('/', $request->input('start_date_store_appointment'));
                        $from = jalali_to_gregorian($from[0], $from[1], $from[2], '-');
                        $from = $from . ' 00:00:00';
                        $query->where('updated_at', '>=', $from);

                    }

                    if ($request->end_date_store_appointment != null) {

                        $from = explode('/', $request->input('end_date_store_appointment'));
                        $from = jalali_to_gregorian($from[0], $from[1], $from[2], '-');
                        $from = $from . ' 23:59:59';
                        $query->where('updated_at', '<=', $from);

                    }

                    if (intval($request->agent_id) != 0) {
                        $query->whereAgentId($request->agent_id);
                    }
                    if (intval($request->part_id) != 0) {
                        $query->whereAppointmentPartId($request->part_id);
                    }
                    if (intval($request->doctor_id) != 0) {
                        $query->whereDoctorId($request->doctor_id);
                    }
                    if (intval($request->kind) != -1) {
                        $query->whereKind($request->kind);
                    }
                    if (intval($request->status) != -1) {
                        if ($request->status == AppointmentUser::STATUS_HAS_IDENTIFIERـCODE) {
                            $query->whereNotNull('detail->' . AppointmentUser::IDENTIFIERـCODE);
                        } elseif ($request->status == AppointmentUser::STATUS_PANAHI) {
                            $query->where('status', '<>', AppointmentUser::STATUS_DELETED);
                        } else {
                            $query->whereStatus($request->status);
                        }
                    }
                    if ($request->code != null) {
                        $query->whereCode($request->code);
                    }
                    $this->applyAppointmentDateRange($query, $request->start_date, $request->end_date);
                    if ($request->start_clock != null) {
                        $query->where('time_from', '>=', $request->start_clock . ":00");
                    }
                    if ($request->end_clock != null) {
                        $query->where('time_to', '<=', $request->end_clock . ":00");
                    }
                    if ($request->appointment_first_type !== '0') {
                        $query->whereRaw("
    JSON_VALID(detail) 
    AND JSON_UNQUOTE(JSON_EXTRACT(detail, '$.APPOINTMENT_FIRST_TYPE')) = ?",
                            [$request->appointment_first_type]
                        );
//                        $query->whereJsonContains('detail->APPOINTMENT_FIRST_TYPE', $request->appointment_first_type);
                    }
                    $this->applyIdentifierCodeFilter($query, $this->identifierCodeSearchTerm($request));
                    if (intval($request->transaction) != 0) {
                        $query->whereHas('transaction', function ($query) use ($request) {
                            /** @var $query Transaction */
                            if (intval($request->transaction) == 1) {
                                $query->whereStatus(Transaction::STATUS_SUCCESS);
                            } else {
                                $query->where('status', '<>', Transaction::STATUS_SUCCESS);
                            }
                        });
                    }
                })->whereHas('user', function ($query) use ($request) {
                    /** @var $query User */
                    if ($request->mobile != null) {
                        $query->whereMobile($request->mobile);
                    }
                    if ($request->acquainted) {
                        $query->whereHas('userMetas', function ($q) use ($request) {
                            $q->where([
                                ['meta_key', 'ACQUAINTED'],
                                ['meta_value', "=", $request->acquainted]
                            ]);
                        });
                    }

                    if ($request->city) {

                        $querySearch = $request->city;
                        $ondition = '=';
                        if (strpos($request->city, '+') !== false) {
                            $querySearch = str_replace("+", "", $request->city);
                            $ondition = '<>';
                        }


                        $query->whereHas('userMetas', function ($q) use ($querySearch, $ondition) {
                            $q->where([
                                ['meta_key', UserMeta::ADDRESS],
                                ['meta_value', $ondition, $querySearch]
                            ])->where([
                                ['meta_key', UserMeta::ADDRESS],
                                ['meta_value', '!=', '']
                            ]);
                        });
                    }
                    if ($request->firstname) {
                        $query->whereHas('userMetas', function ($q) use ($request) {
                            $q->where([
                                ['meta_key', UserMeta::FIRST_NAME],
                                ['meta_value', "LIKE", "%" . $request->firstname . "%"]
                            ]);
                        });
                    }
                    if ($request->lastname) {
                        $query->whereHas('userMetas', function ($q) use ($request) {
                            $q->where([
                                ['meta_key', UserMeta::LAST_NAME],
                                ['meta_value', "LIKE", "%" . $request->lastname . "%"]
                            ]);
                        });
                    }
                })->whereHas('agent', function ($query) use ($request) {
                    if ($request->agent_name != null) {
                        $query->whereHas('userMetas', function ($q) use ($request) {
                            $q->whereIn('meta_key', [UserMeta::FIRST_NAME, UserMeta::LAST_NAME])
                                ->where('meta_value', 'LIKE', '%' . trim($request->agent_name) . '%');
                        });
                    }
                })->get();
            } else {

                $mAppointmentUser = AppointmentUser::withTrashed()->where(function ($query) {
                    if (!Auth::user()->can('admin_type_full_appointment_user_access')) {
                        $doctorRolsid = AppointmentPart::DOCTOR_ROLES;
                        if (in_array(auth()->user()->roles->first()->id, $doctorRolsid)) {
                            $query->whereDoctorId(Auth::id());
                        } else {
                            $query->whereAgentId(Auth::id());
                        }
                    }
                })->get();
            }
        } else {
//            dd(Auth::user()->viewTrasheed());

            if ($request->act != null && $request->act == "search") {

                $mAppointmentUser = AppointmentUser::withTrashed()->where(function ($query) use ($request) {
                    /** @var AppointmentUser $query */
                    if (!Auth::user()->can('admin_type_full_appointment_user_access')) {
                        $doctorRolsid = AppointmentPart::DOCTOR_ROLES;
                        if (in_array(auth()->user()->roles->first()->id, $doctorRolsid)) {
                            $query->whereDoctorId(Auth::id());
                        } else {
                            $query->whereAgentId(Auth::id());
                        }
                    }
                    if (intval($request->user_id) != 0) {
                        $query->whereUserId($request->user_id);
                    }
                    if ($request->start_date_store_appointment != null) {

                        $from = explode('/', $request->input('start_date_store_appointment'));
                        $from = jalali_to_gregorian($from[0], $from[1], $from[2], '-');
                        $from = $from . ' 00:00:00';
                        $query->where('created_at', '>=', $from);

                    }

                    if ($request->end_date_store_appointment != null) {

                        $from = explode('/', $request->input('end_date_store_appointment'));
                        $from = jalali_to_gregorian($from[0], $from[1], $from[2], '-');
                        $from = $from . ' 23:59:59';
                        $query->where('created_at', '<=', $from);

                    }
                    if (intval($request->agent_id) != 0) {
                        $query->whereAgentId($request->agent_id);
                    }
                    if (intval($request->part_id) != 0) {
                        $query->whereAppointmentPartId($request->part_id);
                    }
                    if (intval($request->doctor_id) != 0) {
                        $query->whereDoctorId($request->doctor_id);
                    }
                    if (intval($request->kind) != -1) {
                        $query->whereKind($request->kind);
                    }

                    if (intval($request->status) != -1) {
                        if ($request->status == AppointmentUser::STATUS_HAS_IDENTIFIERـCODE) {
                            $query->whereNotNull('detail->' . AppointmentUser::IDENTIFIERـCODE);
                        } elseif ($request->status == AppointmentUser::STATUS_PANAHI) {
                            $query->where('status', '<>', AppointmentUser::STATUS_DELETED);
                        } else {
                            $query->whereStatus($request->status);
                        }
                    }

                    if ($request->code != null) {
                        $query->whereCode($request->code);
                    }
                    $this->applyAppointmentDateRange($query, $request->start_date, $request->end_date);
                    if ($request->start_clock != null) {
                        $query->where('time_from', '>=', $request->start_clock . ":00");
                    }
                    if ($request->end_clock != null) {
                        $query->where('time_to', '<=', $request->end_clock . ":00");
                    }
                    if ($request->appointment_first_type !== '0') {
                        $query->whereRaw("
    JSON_VALID(detail)
    AND JSON_UNQUOTE(JSON_EXTRACT(detail, '$.APPOINTMENT_FIRST_TYPE')) = ?",
                            [$request->appointment_first_type]
                        );
//                        $query->whereJsonContains('detail->APPOINTMENT_FIRST_TYPE', $request->appointment_first_type);
                    }
                    $this->applyIdentifierCodeFilter($query, $this->identifierCodeSearchTerm($request));

                    if (intval($request->transaction) != 0) {
                        $query->whereHas('transaction', function ($query) use ($request) {
                            /** @var $query Transaction */
                            if (intval($request->transaction) == 1) {
                                $query->whereStatus(Transaction::STATUS_SUCCESS);
                            } else {
                                $query->where('status', '<>', Transaction::STATUS_SUCCESS);
                            }
                        });
                    }
                })->whereHas('user', function ($query) use ($request) {
                    /** @var $query User */
                    if ($request->mobile != null) {
                        $query->whereMobile($request->mobile);
                    }
                    if ($request->acquainted) {
                        $query->whereHas('userMetas', function ($q) use ($request) {
                            $q->where([
                                ['meta_key', 'ACQUAINTED'],
                                ['meta_value', "=", $request->acquainted]
                            ]);
                        });
                    }

                    if ($request->city) {

                        $querySearch = $request->city;
                        $ondition = '=';
                        if (strpos($request->city, '+') !== false) {
                            $querySearch = str_replace("+", "", $request->city);
                            $ondition = '<>';
                        }


                        $query->whereHas('userMetas', function ($q) use ($querySearch, $ondition) {
                            $q->where([
                                ['meta_key', UserMeta::ADDRESS],
                                ['meta_value', $ondition, $querySearch]
                            ])->where([
                                ['meta_key', UserMeta::ADDRESS],
                                ['meta_value', '!=', '']
                            ]);
                        });
                    }
                    if ($request->firstname) {
                        $query->whereHas('userMetas', function ($q) use ($request) {
                            $q->where([
                                ['meta_key', UserMeta::FIRST_NAME],
                                ['meta_value', "LIKE", "%" . $request->firstname . "%"]
                            ]);
                        });
                    }
                    if ($request->lastname) {
                        $query->whereHas('userMetas', function ($q) use ($request) {
                            $q->where([
                                ['meta_key', UserMeta::LAST_NAME],
                                ['meta_value', "LIKE", "%" . $request->lastname . "%"]
                            ]);
                        });
                    }
                })->whereHas('agent', function ($query) use ($request) {
                    if ($request->agent_name != null) {
                        $query->whereHas('userMetas', function ($q) use ($request) {
                            $q->whereIn('meta_key', [UserMeta::FIRST_NAME, UserMeta::LAST_NAME])
                                ->where('meta_value', 'LIKE', '%' . trim($request->agent_name) . '%');
                        });
                    }
                })->orderBy('id', 'DESC')->paginate(30)->appends(request()->query());
            } else {
                $mAppointmentUser = AppointmentUser::withTrashed()->where(function ($query) {
                    if (!Auth::user()->can('admin_type_full_appointment_user_access')) {
                        $doctorRolsid = AppointmentPart::DOCTOR_ROLES;
                        if (in_array(auth()->user()->roles->first()->id, $doctorRolsid)) {
                            $query->whereDoctorId(Auth::id());
                        } else {
                            $query->whereAgentId(Auth::id());
                        }

                    }
                })->orderBy('id', 'DESC')->paginate(50)->appends(request()->query());

            }
        }
        if ($request->export != null && $request->export == "1") {
            $dataSheets = [];
            $multiplied = $mAppointmentUser->map(function ($item, $key) {
                /** @var AppointmentUser timestamp */
                $item->timestamp = jmktime(0, 0, 0, $item->month, $item->day, $item->year);
                return $item;
            });
            $uniqueDay = Arr::sort(array_keys($multiplied->groupBy('timestamp')->toArray()));
            foreach ($uniqueDay as $item) {
                $dataSheets[] = [
                    'title' => jdate('l d F', $item),
                    'titleExellPage' => "لیست نوبت ها برای " . jdate('l d F Y', $item),
                    "items" => $mAppointmentUser->where('timestamp', $item)->pluck('id')->toArray()
                ];
            }
            return new AppointmentUserExport($dataSheets);
        }


        if ($this->isSuperAdmin()) {
            $mAppointmentUser->getCollection()->load('identifierHistories.actor');
        }

        //dd(Auth::user()->checkPermissionTo('admin_type_limited_appointment_user_access_cancel'));
        return view('appointment::admin.appointment_user.index', compact('mAppointmentUser', 'parts', 'doctors'));
    }

    private function chanePartName($title, $partName)
    {

        $partTimeOut = '';
        if (count($partName) > 0 && is_assoc($partName)) {

            $partTimeOut = ' - [ ';
            $i = 0;
            foreach ($partName as $name => $time) {
                $i++;
                $partTimeOut .= $name;
                if ($i < count($partName)) $partTimeOut .= ' - ';
            }
            $partTimeOut .= ' ]';
        }
        return $title . $partTimeOut;
    }

    private function convertNumber($string)
    {
        $persinaDigits1 = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
        $persinaDigits2 = array('٩', '٨', '٧', '٦', '٥', '٤', '٣', '٢', '١', '٠');
        $allPersianDigits = array_merge($persinaDigits1, $persinaDigits2);
        $replaces = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '9', '8', '7', '6', '5', '4', '3', '2', '1', '0');
        return str_replace($allPersianDigits, $replaces, $string);
    }

    private function formatAppointments($appointments)
    {
        return $appointments->map(function ($appointment) {
            $detail = is_array($appointment->detail) ? $appointment->detail : [];
            $user = $appointment->user;
            $originalTimeFrom = substr($appointment->time_from, 0, 5);
            $confirmedTimeFrom = $appointment->confirmed_time_from ? substr($appointment->confirmed_time_from, 0, 5) : null;
            $listTimeFrom = $confirmedTimeFrom ?: $originalTimeFrom;
            $originalServiceName = $detail[UserMeta::SERVICE_DETAIL] ?? ($detail[UserMeta::SERVICE] ?? '');
            $confirmedServiceName = $appointment->confirmed_service_name ?: null;
            $listServiceName = $confirmedServiceName ?: $originalServiceName;

            // تعریف وضعیت بر اساس شرایط
            $status = "تایید شده"; // وضعیت پیش‌فرض

            if ($appointment->status == AppointmentUser::STATUS_CANCELED) {
                $status = "کنسل شده";
            } elseif ($appointment->status == AppointmentUser::STATUS_PENDING) {
                $status = "در انتظار";
            } elseif ($appointment->status == AppointmentUser::STATUS_DELETED) {
                $status = "حذف شده";
            } elseif ($appointment->status == AppointmentUser::STATUS_PENDING_CALL) {
                $status = "تلفنی بدون وقت";
            }

            return [
                'id' => $appointment->id,
                'user_id' => $appointment->user_id,
                'doctor' => ['id' => $appointment->doctor_id, 'name' => (optional($appointment->doctor)->full_name ?? ''),],
                'registered_by' => (optional($appointment->agent)->full_name ?? ''),
                'kind' => ($appointment->kind == AppointmentUser::KIND_MAIN_APPOINTMENT) ? "نوبت اصلی" : "نوبت بین مریض",
                'user' => [
                    'id' => optional($user)->id,
                    'name' => optional($user)->first_name,
                    'gender' => optional($user)->gender == 1 ? 'مرد' : 'زن',
                    'last_name' => optional($user)->last_name,
                    'mobile' => optional($user)->mobile,
                    'email' => optional($user)->email,
                    'national_code' => optional($user)->national_code,
                    'medical_record_number' => optional($user)->medical_record_number,
                ],
                'part_name' => $this->chanePartName(($appointment->appointmentPart->title ?? ''), $appointment->r_part),

                'code' => $appointment->code,
                'time_from' => $appointment->time_from,
                'time_to' => $appointment->time_to,
                'list_time_from' => $listTimeFrom,
                'original_time_from' => $originalTimeFrom,
                'confirmed_time_from' => $confirmedTimeFrom,
                'time_changed' => $confirmedTimeFrom != null && $confirmedTimeFrom !== $originalTimeFrom,
                'status' => [
                    'title' => $status,
                    'value' => $appointment->status
                ],  // استفاده از وضعیت جدید
                'p_date' =>             jdate('l j F', jmktime(0, 0, 0, $appointment->month, $appointment->day, $appointment->year)),
                'p_date_fotmat' => $this->convertNumber(jdate('Y/m/d', jmktime(0, 0, 0, $appointment->month, $appointment->day, $appointment->year))),
                'acquainted' => (optional($user)->acquainted && intval(optional($user)->acquainted)) ? (Appointment::$ACQUAINTED[optional($user)->acquainted] ?? optional($user)->acquainted) : optional($user)->acquainted,
                'service' =>                     (isset($detail[UserMeta::SERVICE]) ? ($detail[UserMeta::SERVICE] == 'undefined' ? '' : $detail[UserMeta::SERVICE]) : ' ' ),
                'service_detail' =>             ($detail[UserMeta::SERVICE_DETAIL] ?? ''),
                'list_service_name' => $listServiceName,
                'original_service_name' => $originalServiceName,
                'confirmed_service_name' => $confirmedServiceName,
                'service_changed' => $confirmedServiceName != null && $confirmedServiceName !== $originalServiceName,
                'identifier_code' => ($detail[AppointmentUser::IDENTIFIERـCODE] ?? ''),
                'medical_record_number' => optional($user)->medical_record_number
                    ?: ($detail[AppointmentUser::MEDICAL_RECORD_NUMBER] ?? ''),
                'second_mobile' => ($detail[AppointmentUser::SECOND_MOBILE] ?? ' '),
                'is_confirmed' => (bool) $appointment->is_confirmed,
                'confirmed_by' => [
                    'id' => $appointment->confirmed_by,
                    'name' => optional($appointment->confirmedBy)->full_name,
                ],
                'confirmed_at' => $appointment->confirmed_at ? $appointment->confirmed_at->toDateTimeString() : null,
                'confirmed_at_jalali' => $appointment->confirmed_at ? jdate('H:i | Y/m/d', $appointment->confirmed_at->timestamp, '', '', 'en') : null,
                'confirmed_comment' => $appointment->confirmed_comment,
                'comment' => $appointment->confirmed_comment,
                'description' => $appointment->confirmed_comment,
                'created_at' => $appointment->created_at,
                'updated_at' => $appointment->updated_at,
            ];
        });
    }

    public function todayList(Request $request)
    {
        $startDate = $this->selectedDate($request, 0);
        $date = explode("/", $startDate);


        $mAppointmentUser = AppointmentUser::withTrashed()
            ->with(['user', 'agent', 'doctor', 'appointmentPart', 'confirmedBy']);
        $mAppointmentUser->where([["year", $date[0]], ["month", $date[1]], ["day", $date[2]]]);
        $mAppointmentUser->where('is_confirmed', true)
            ->orderByRaw('COALESCE(confirmed_time_from, time_from)')
            ->orderBy('id');

        return response()->json([
            'status' => 'success',
            'appointments' => $this->formatAppointments($mAppointmentUser->get())
        ], 200, [], JSON_UNESCAPED_UNICODE);

    }


    function create()
    {
        // TODO: Implement create() method.
    }

    /**
     * Edit user appointments
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    function edit($id)
    {
        $mAppointmentUser = AppointmentUser::findOrFail($id);
        $oldValues = AdminActivityLogger::modelValues($mAppointmentUser);
        $act = request()->act;
        $message = '';
        switch ($act) {
            case 'cancel':
                $mAppointmentUser->status = AppointmentUser::STATUS_CANCELED;
                if (request()->get('sms') == 'send') {
//                    $messgae = appointmentUserTextFilter(get_option(Appointment::SETTING_SMS_AFTER_CANCEL_MESSAGE), $mAppointmentUser);
//                    sendSmsNow($mAppointmentUser->id, $messgae, "Modules\Appointment\Entities\AppointmentUser");
                    $time = explode(':', $mAppointmentUser->time_from);
                    $secondMobile = trim((string) ($mAppointmentUser->detail[AppointmentUser::SECOND_MOBILE]
                        ?? optional($mAppointmentUser->user)->second_mobile
                        ?? ''));
                    $sendToPrimary = !request()->has('send_sms_to_primary_mobile')
                        || request()->get('send_sms_to_primary_mobile') === '1';
                    $sendToSecond = request()->get('send_sms_to_second_mobile') === '1';
                    $recipients = [];

                    if ($sendToPrimary) {
                        $recipients[] = $mAppointmentUser->user->mobile;
                    }
                    if ($sendToSecond && !empty($secondMobile)) {
                        $recipients[] = $secondMobile;
                    }

                    foreach (array_unique(array_filter($recipients)) as $recipient) {
                        smsNow($recipient,
                            'kamiloCancel',
                            "{$mAppointmentUser->year}/{$mAppointmentUser->month}/{$mAppointmentUser->day}",
                            "{$time[0]}:{$time[1]}",
                            $mAppointmentUser->detail[AppointmentUser::SHORT_LINK] ?? ''
                        );
                    }
                }
                $message = "نوبت " . $mAppointmentUser->code . " با موفقیت کنسل شد";
                break;
            case 'approve':
                $mAppointmentUser->status = AppointmentUser::STATUS_OK;
                $message = "نوبت " . $mAppointmentUser->code . " با موفقیت تایید شد";
                break;
            case 'change_to_main':
                $mAppointmentUser->kind = AppointmentUser::KIND_MAIN_APPOINTMENT;
                $message = "نوبت " . $mAppointmentUser->code . " با موفقیت به نوبت اصلی تبدیل شد.";
                break;
            case 'change_to_subsidiary':
                $mAppointmentUser->kind = AppointmentUser::KIND_SUBSIDIARY_APPOINTMENT;
                $message = "نوبت " . $mAppointmentUser->code . " با موفقیت به نوبت بین مریض تبدیل شد.";
                break;
        }
        $mAppointmentUser->save();
        AdminActivityLogger::log('appointment.action.' . $act, $mAppointmentUser, $oldValues, AdminActivityLogger::modelValues($mAppointmentUser), [
            'module' => 'appointment',
            'description' => 'عملیات مدیریتی روی نوبت انجام شد',
            'request_payload' => request()->except(['_token']),
        ]);
        if (request()->wantsJson()) {
            return response()->json(['message' => $message], Response::HTTP_OK, array(), JSON_UNESCAPED_UNICODE);
        } else {
            request()->session()->flash('successful', $message);
            return redirect()->route($this->routeBaseName . "index");
        }
    }

    function apiStore(Request $request)
    {
        // TODO: Implement apiStore() method.
    }

    function webStore(Request $request)
    {
        // TODO: Implement webStore() method.
    }


    function apiShow(Request $request, $id)
    {
        // TODO: Implement apiShow() method.
    }

    function webShow(Request $request, $id)
    {
        // TODO: Implement webShow() method.
    }

    function apiUpdate(Request $request, $id)
    {
        // TODO: Implement apiUpdate() method.
    }

    /**
     * Change appointment status and kind
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    function webUpdate(Request $request, $id)
    {
        $act = $request->act;
        $appointments = $request->appointment_user;
        if ($act == "approve") {
            foreach ($appointments as $id) {
                $appointment = AppointmentUser::find($id);
                if ($appointment->exists() && $appointment->status == AppointmentUser::STATUS_PENDING) {
                    $oldValues = AdminActivityLogger::modelValues($appointment);
                    $appointment->status = AppointmentUser::STATUS_OK;
                    $appointment->save();
                    AdminActivityLogger::log('appointment.group.approved', $appointment, $oldValues, AdminActivityLogger::modelValues($appointment), [
                        'module' => 'appointment',
                        'description' => 'نوبت در عملیات گروهی تایید شد',
                    ]);
                }
            }
            request()->session()->flash('successful', "نوبت‌های انتخابی با موفقیت تایید شدن");
            return redirect()->route($this->routeBaseName . "index");
        } elseif ($act == "delete") {
            if (!Auth::user()->can('delete_admin_appointment_user')) {
                request()->session()->flash('sys_error', "شما به حذف نوبت ها دسترسی ندارید");
            }
            $errors = [];
            $successShow = false;

            foreach ($appointments as $id) {
                $appointment = AppointmentUser::find($id);
                if (
                    (checkAccessDeleteOrUpdate() == false) &&
                    (Auth::user()->can('admin_type_limited_appointment_user_access_delete') && Auth::id() != $appointment->agent_id)
                ) {
                    $errors[] = 'فقط نوبت هایی که خودتان ثبت کردید را میتوانید حذف کنید - امکان حذف نوبت برای ' . $appointment->user->full_name . ' را ندارید';
                    continue;
                }
                if ($appointment->exists()) {
                    try {
                        $successShow = true;
                        $oldValues = AdminActivityLogger::modelValues($appointment);
                        $appointment->status = AppointmentUser::STATUS_DELETED;
                        $appointment->save();
                        $appointment->delete();
                        AdminActivityLogger::log('appointment.group.deleted', $appointment, $oldValues, [], [
                            'module' => 'appointment',
                            'description' => AdminActivityLogger::appointmentDeletionDescription($appointment, 'عملیات گروهی پنل مدیریت'),
                        ]);
                    } catch (\Exception $e) {
                    }
                }
            }
            if (count($errors) > 0) {
                $errors = implode(" ", $errors);
                request()->session()->flash('sys_error', $errors);
            }
            if ($successShow) {
                request()->session()->flash('successful', "نوبت‌های انتخابی با موفقیت حذف شدن");
            }
            return redirect()->route($this->routeBaseName . "index");
        } elseif ($act == 'cancel') {

            if (!Auth::user()->can('cancel_admin_appointment_user')) {
                request()->session()->flash('sys_error', "شما به کنسل کردن نوبت ها دسترسی ندارید");
            }
            $errors = [];
            $successShow = false;

            $smsStatus = $request->sms_status;
            foreach ($appointments as $id) {
                $appointment = AppointmentUser::find($id);
                if (
                    (checkAccessDeleteOrUpdate() == false) &&
                    (Auth::user()->can('admin_type_limited_appointment_user_access_cancel') && Auth::id() != $appointment->agent_id)
                ) {
                    $errors[] = 'فقط نوبت هایی که خودتان ثبت کردید را میتوانید کنسل کنید - امکان کنسلی نوبت برای ' . $appointment->user->full_name . ' را ندارید';
                    continue;
                }
                if ($appointment->exists() && ($appointment->status == AppointmentUser::STATUS_OK || $appointment->status == AppointmentUser::STATUS_PENDING)) {
                    $oldValues = AdminActivityLogger::modelValues($appointment);
                    $appointment->status = AppointmentUser::STATUS_CANCELED;
                    if ($smsStatus == "1") {
                        $time = explode(':', $appointment->time_from);
                        smsNow($appointment->user->mobile,
                            'kamiloCancel',
                            "{$appointment->year}/{$appointment->month}/{$appointment->day}",
                            "{$time[0]}:{$time[1]}",
                            $appointment->detail[AppointmentUser::SHORT_LINK] ?? ''
                        );

                    }
                    $successShow = true;
                    $appointment->save();
                    AdminActivityLogger::log('appointment.group.canceled', $appointment, $oldValues, AdminActivityLogger::modelValues($appointment), [
                        'module' => 'appointment',
                        'description' => 'نوبت در عملیات گروهی کنسل شد',
                    ]);
                }
            }
            if (count($errors) > 0) {
                $errors = implode(" ", $errors);
                request()->session()->flash('sys_error', $errors);
            }
            if ($successShow) {
                request()->session()->flash('successful', "نوبت‌های انتخابی با موفقیت کنسل شدن");
            }
            return redirect()->route($this->routeBaseName . "index");
        } else {
            request()->session()->flash('sys_error', "عملیات انتخابی شما پشتیبانی نمی‌شود");
            return redirect()->route($this->routeBaseName . "index");
        }
    }

    function apiDestroy(Request $request, $id)
    {
        $appointment = AppointmentUser::findOrFail($id);
        $oldValues = AdminActivityLogger::modelValues($appointment);
        $status = true;
        try {
            $appointment->status = AppointmentUser::STATUS_DELETED;
            $appointment->save();
            $appointment->delete();
            AdminActivityLogger::log('appointment.deleted.api', $appointment, $oldValues, [], [
                'module' => 'appointment',
                'description' => AdminActivityLogger::appointmentDeletionDescription($appointment, 'API مدیریت'),
            ]);
            $request->session()->flash('successful', 'نوبت با موفقیت حذف شد');
        } catch (\Exception $e) {
            $status = false;
            $request->session()->flash('sys_error', 'در حال حاضر امکان حذف نوبت وجود ندارد');
        }
        return response()->json([$status], 200, array(), JSON_UNESCAPED_UNICODE);
    }

    /**
     * Web remove
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    function webDestroy(Request $request, $id)
    {

        $appointment = AppointmentUser::findOrFail($id);
        $oldValues = AdminActivityLogger::modelValues($appointment);
        try {
            $appointment->status = AppointmentUser::STATUS_DELETED;
            $appointment->save();
            $appointment->delete();
            AdminActivityLogger::log('appointment.deleted.web', $appointment, $oldValues, [], [
                'module' => 'appointment',
                'description' => AdminActivityLogger::appointmentDeletionDescription($appointment, 'پنل مدیریت'),
            ]);
            $request->session()->flash('successful', 'نوبت با موفقیت حذف شد');
        } catch (\Exception $e) {
            $request->session()->flash('sys_error', 'در حال حاضر امکان حذف نوبت وجود ندارد');
        }
        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param AppointmentUser $appointment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendStandardSmsStore(Request $request, AppointmentUser $appointment)
    {
        $request->validate([
            'template' => 'required|string',
            'time_from' => ['required', 'regex:/^([01][0-9]|2[0-3]):[0-5][0-9]$/'],
        ], [
            'time_from.required' => 'لطفاً ساعت حضور را وارد کنید.',
            'time_from.regex' => 'ساعت حضور باید با قالب صحیح، مانند 10:00، وارد شود.',
        ]);

        $partName = $appointment->appointmentPart->title;
        $doctorFullName = $appointment->doctor->full_name;
        $template = $request->get('template');
        $time_from = $request->get('time_from');

        $firstName = $appointment->user->first_name;
        $mobile = $appointment->user->mobile;
        $secondMobile = trim((string) ($appointment->detail[AppointmentUser::SECOND_MOBILE]
            ?? optional($appointment->user)->second_mobile
            ?? ''));
        $recipients = [];
        if ($request->input('send_sms_to_primary_mobile') === '1') {
            $recipients[] = $mobile;
        }
        if ($request->input('send_sms_to_second_mobile') === '1' && !empty($secondMobile)) {
            $recipients[] = $secondMobile;
        }

        $recipients = array_values(array_unique(array_filter($recipients)));
        if (empty($recipients)) {
            $request->session()->flash('sys_error', 'حداقل یک شماره برای ارسال پیامک انتخاب کنید');
            return redirect()->back();
        }

        $link = $appointment->detail['SHORT_LINK'] ?? '';

        //date appointment
        $day = $appointment->day;
        $month = $appointment->month;
        $year = $appointment->year;
        $dateAppointment = "{$year}/{$month}/{$day}";
        $dayName = jdate('l', jmktime(0, 0, 0, $month, $day, $year));


        //date appointment

        $detail = $appointment->detail;
        $detail['STANDARD_SMS'][$template] = [
            'send' => true,
            'admin_id' => Auth::id(),
            'send_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];
        $appointment->detail = $detail;
        $appointment->save();
        foreach ($recipients as $recipient) {
            if ($template == 'kamiloReminder1' || $template == 'kamiloReminder2') {
                smsNow($recipient, $template, $firstName, $time_from, $link);
            } elseif ($template == 'kamiloReminder3' || $template == 'kamiloReminder4') {
                smsNow($recipient, $template, $firstName, $dateAppointment, $dayName, $time_from, $link);
            } elseif ($template == 'kamiloReminder5') {
                smsNow($recipient, $template, $firstName, $dateAppointment, $dayName, $link);
            } elseif ($template == 'kamiloReminder6' || $template == 'kamiloReminder9') {
                smsNow($recipient, $template, $firstName, $dateAppointment, $dayName, $time_from);
            } elseif ($template == 'kamiloReminder7' || $template == 'kamiloReminder8') {
                smsNow($recipient, $template, $firstName, $dateAppointment, $dayName);
            } elseif ($template == 'kamiloReminder10') {
                smsNow($recipient, $template, $firstName);
            } elseif ($template == 'kamiloReminder11' || $template == 'kamiloReminder12' || $template == 'kamiloReminder14') {
                smsNow($recipient, $template, $firstName, $dayName, $dateAppointment, $time_from, $partName, $doctorFullName, $link);
            } elseif ($template == 'kamiloReminder13' || $template == 'kamiloReminder15' || $template == 'kamiloReminder16') {
                smsNow($recipient, $template, $firstName);
            } else {
                smsNow($recipient, $template, $firstName);
            }
        }

        $request->session()->flash('successful', 'پیامک با موفقیت ارسال شد');
        return redirect()->back();

    }

    public function sendStandardSms(AppointmentUser $appointment)
    {
        $time = explode(':', $appointment->time_from);
        $timeFrom = "{$time[0]}:{$time[1]}";
        return view('appointment::admin.standard_sms.index', compact('appointment', 'timeFrom'));

    }
}

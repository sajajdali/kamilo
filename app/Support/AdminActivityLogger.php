<?php

namespace App\Support;

use App\AdminActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Throwable;

class AdminActivityLogger
{
    protected static $sensitiveKeys = [
        'password',
        'password_confirmation',
        'current_password',
        'new_password',
        'token',
        '_token',
        'remember_token',
    ];

    public static function log($action, $subject = null, array $oldValues = [], array $newValues = [], array $context = [])
    {
        try {
            $request = request();
            $admin = Auth::user();
            $subjectType = $subject instanceof Model ? get_class($subject) : Arr::get($context, 'subject_type');
            $subjectId = $subject instanceof Model ? $subject->getKey() : Arr::get($context, 'subject_id');
            $route = $request instanceof Request ? $request->route() : null;

            AdminActivityLog::create([
                'admin_id' => $admin ? $admin->id : null,
                'admin_email' => $admin ? $admin->email : null,
                'admin_name' => $admin ? trim($admin->full_name) : null,
                'action' => $action,
                'module' => Arr::get($context, 'module', self::guessModule($subjectType, $request)),
                'subject_type' => $subjectType,
                'subject_id' => $subjectId,
                'description' => Arr::get($context, 'description'),
                'old_values' => self::sanitize($oldValues),
                'new_values' => self::sanitize($newValues),
                'request_payload' => self::sanitize(Arr::get($context, 'request_payload', [])),
                'ip' => $request instanceof Request ? $request->ip() : null,
                'user_agent' => $request instanceof Request ? $request->userAgent() : null,
                'method' => $request instanceof Request ? $request->method() : null,
                'url' => $request instanceof Request ? $request->fullUrl() : null,
                'route_name' => $route ? $route->getName() : null,
                'controller_action' => $route ? $route->getActionName() : null,
                'response_status' => Arr::get($context, 'response_status'),
            ]);
        } catch (Throwable $e) {
            // Audit logging must never break the admin action itself.
        }
    }

    public static function modelValues(Model $model)
    {
        return self::sanitize($model->attributesToArray());
    }

    public static function appointmentDeletionDescription($appointment, $source = 'پنل مدیریت')
    {
        $admin = Auth::user();
        $patient = $appointment->user;
        $agent = $appointment->agent;
        $part = $appointment->appointmentPart;
        $patientName = $patient ? trim((string) $patient->full_name) : '';
        $agentName = $agent ? trim((string) $agent->full_name) : '';
        $adminName = $admin ? trim((string) $admin->full_name) : '';
        $request = request();
        $deletedAt = time();
        $createdAt = $appointment->created_at ? strtotime((string) $appointment->created_at) : null;
        $appointmentDate = sprintf('%04d/%02d/%02d', $appointment->year, $appointment->month, $appointment->day);

        return implode(PHP_EOL, [
            sprintf('نوبت %s به نام %s با شماره موبایل %s بود.', $appointment->id, $patientName !== '' ? $patientName : 'کاربر نامشخص', $patient ? $patient->mobile : 'نامشخص'),
            sprintf('این نوبت توسط %s (شناسه کاربر: %s، شماره موبایل: %s) ثبت شده بود.', $agentName !== '' ? $agentName : 'کاربر نامشخص', $agent ? $agent->id : ($appointment->agent_id ?: 'نامشخص'), $agent ? $agent->mobile : 'نامشخص'),
            sprintf('بخش نوبت: %s', $part ? $part->title : 'نامشخص'),
            sprintf('تاریخ ثبت نوبت: %s', $createdAt ? jdate('Y/m/d H:i:s', $createdAt, '', '', 'en') : 'نامشخص'),
            sprintf('تاریخ نوبت: %s ساعت %s', $appointmentDate, substr((string) $appointment->time_from, 0, 5)),
            sprintf('حذف‌کننده: %s (شناسه کاربر: %s، شماره موبایل: %s)', $adminName !== '' ? $adminName : 'کاربر نامشخص', $admin ? $admin->id : 'نامشخص', $admin ? $admin->mobile : 'نامشخص'),
            sprintf('تاریخ حذف: %s', jdate('Y/m/d', $deletedAt, '', '', 'en')),
            sprintf('ساعت حذف: %s', jdate('H:i:s', $deletedAt, '', '', 'en')),
            sprintf('IP: %s', $request instanceof Request ? $request->ip() : 'نامشخص'),
            sprintf('روش حذف: %s', $source),
        ]);
    }

    public static function changedOldValues(Model $model)
    {
        $old = [];
        foreach ($model->getChanges() as $key => $value) {
            if ($key === 'updated_at') {
                continue;
            }
            $old[$key] = $model->getOriginal($key);
        }
        return self::sanitize($old);
    }

    public static function changedNewValues(Model $model)
    {
        $changes = $model->getChanges();
        unset($changes['updated_at']);
        return self::sanitize($changes);
    }

    public static function sanitize(array $values)
    {
        foreach ($values as $key => $value) {
            if (in_array($key, self::$sensitiveKeys, true)) {
                $values[$key] = '[FILTERED]';
                continue;
            }

            if (is_array($value)) {
                $values[$key] = self::sanitize($value);
            }
        }

        return $values;
    }

    protected static function guessModule($subjectType, $request)
    {
        if ($subjectType && strpos($subjectType, 'Modules\\Appointment') === 0) {
            return 'appointment';
        }

        if ($subjectType === 'App\\User') {
            return 'user';
        }

        if ($request instanceof Request && strpos($request->path(), 'admin/appointment') === 0) {
            return 'appointment';
        }

        return 'admin';
    }
}

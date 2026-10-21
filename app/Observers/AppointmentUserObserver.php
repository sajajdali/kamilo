<?php

namespace App\Observers;

use App\Support\AdminActivityLogger;
use App\User;
use Illuminate\Support\Facades\Auth;
use Modules\Appointment\Entities\AppointmentIdentifierHistory;
use Modules\Appointment\Entities\AppointmentUser;

class AppointmentUserObserver
{
    public function created(AppointmentUser $appointmentUser)
    {
        $identifier = $this->identifierFromDetail($appointmentUser->detail);
        if ($identifier !== null) {
            $this->recordIdentifierChange($appointmentUser, AppointmentIdentifierHistory::ACTION_ADDED, null, $identifier);
        }

        AdminActivityLogger::log('appointment.created', $appointmentUser, [], AdminActivityLogger::modelValues($appointmentUser), [
            'module' => 'appointment',
            'description' => 'نوبت ثبت شد',
        ]);
    }

    public function updated(AppointmentUser $appointmentUser)
    {
        if ($appointmentUser->isDirty('detail')) {
            $oldIdentifier = $this->identifierFromDetail($appointmentUser->getOriginal('detail'));
            $newIdentifier = $this->identifierFromDetail($appointmentUser->detail);

            if ($oldIdentifier !== $newIdentifier) {
                if ($oldIdentifier === null) {
                    $action = AppointmentIdentifierHistory::ACTION_ADDED;
                } elseif ($newIdentifier === null) {
                    $action = AppointmentIdentifierHistory::ACTION_REMOVED;
                } else {
                    $action = AppointmentIdentifierHistory::ACTION_UPDATED;
                }

                $this->recordIdentifierChange($appointmentUser, $action, $oldIdentifier, $newIdentifier);
            }
        }

        $newValues = AdminActivityLogger::changedNewValues($appointmentUser);
        if (empty($newValues)) {
            return;
        }

        AdminActivityLogger::log('appointment.updated', $appointmentUser, AdminActivityLogger::changedOldValues($appointmentUser), $newValues, [
            'module' => 'appointment',
            'description' => 'نوبت ویرایش شد',
        ]);
    }

    public function deleted(AppointmentUser $appointmentUser)
    {
        AdminActivityLogger::log('appointment.deleted', $appointmentUser, AdminActivityLogger::modelValues($appointmentUser), [], [
            'module' => 'appointment',
            'description' => AdminActivityLogger::appointmentDeletionDescription($appointmentUser),
        ]);
    }

    private function identifierFromDetail($detail)
    {
        if (is_string($detail)) {
            $detail = json_decode($detail, true);
        }

        if (!is_array($detail)) {
            return null;
        }

        $value = trim((string) ($detail[AppointmentUser::IDENTIFIERـCODE] ?? ''));

        return $value === '' ? null : $value;
    }

    private function recordIdentifierChange(AppointmentUser $appointmentUser, $action, $oldValue, $newValue)
    {
        // During an admin change Auth is authoritative. On initial creation some
        // booking flows set agent_id without authenticating that user.
        $actorId = Auth::id() ?: $appointmentUser->agent_id;
        $actor = $actorId ? User::find($actorId) : null;

        AppointmentIdentifierHistory::create([
            'appointment_user_id' => $appointmentUser->id,
            'changed_by' => $actorId,
            'changed_by_name' => $actor ? trim((string) $actor->full_name) : null,
            'changed_by_mobile' => $actor ? $actor->mobile : null,
            'ip' => app()->runningInConsole() ? null : request()->ip(),
            'action' => $action,
            'old_value' => $oldValue,
            'new_value' => $newValue,
        ]);
    }
}

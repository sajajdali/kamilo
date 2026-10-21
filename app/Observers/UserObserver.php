<?php

namespace App\Observers;

use App\Support\AdminActivityLogger;
use App\User;

class UserObserver
{
    public function created(User $user)
    {
        AdminActivityLogger::log('user.created', $user, [], AdminActivityLogger::modelValues($user), [
            'module' => 'user',
            'description' => 'کاربر ایجاد شد',
        ]);
    }

    public function updated(User $user)
    {
        $newValues = AdminActivityLogger::changedNewValues($user);
        if (empty($newValues)) {
            return;
        }

        AdminActivityLogger::log('user.updated', $user, AdminActivityLogger::changedOldValues($user), $newValues, [
            'module' => 'user',
            'description' => 'اطلاعات کاربر ویرایش شد',
        ]);
    }

    public function deleted(User $user)
    {
        AdminActivityLogger::log('user.deleted', $user, AdminActivityLogger::modelValues($user), [], [
            'module' => 'user',
            'description' => 'کاربر حذف شد',
        ]);
    }
}

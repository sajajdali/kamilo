<?php

namespace App\Observers;

use App\Support\AdminActivityLogger;
use Illuminate\Database\Eloquent\Model;

class GenericAdminModelObserver
{
    public function created(Model $model)
    {
        AdminActivityLogger::log($this->action($model, 'created'), $model, [], AdminActivityLogger::modelValues($model), [
            'module' => $this->module($model),
            'description' => 'رکورد مدیریتی ایجاد شد',
        ]);
    }

    public function updated(Model $model)
    {
        $newValues = AdminActivityLogger::changedNewValues($model);
        if (empty($newValues)) {
            return;
        }

        AdminActivityLogger::log($this->action($model, 'updated'), $model, AdminActivityLogger::changedOldValues($model), $newValues, [
            'module' => $this->module($model),
            'description' => 'رکورد مدیریتی ویرایش شد',
        ]);
    }

    public function deleted(Model $model)
    {
        AdminActivityLogger::log($this->action($model, 'deleted'), $model, AdminActivityLogger::modelValues($model), [], [
            'module' => $this->module($model),
            'description' => 'رکورد مدیریتی حذف شد',
        ]);
    }

    protected function action(Model $model, $event)
    {
        return str_replace('\\', '.', get_class($model)) . '.' . $event;
    }

    protected function module(Model $model)
    {
        $class = get_class($model);

        if (strpos($class, 'Modules\\Appointment') === 0) {
            return 'appointment';
        }

        if ($class === 'App\\UserMeta') {
            return 'user';
        }

        if (strpos($class, 'Spatie\\Permission') === 0) {
            return 'role';
        }

        return 'admin';
    }
}

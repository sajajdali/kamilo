<?php

namespace App\Providers;

use App\Observers\AppointmentUserObserver;
use App\Observers\GenericAdminModelObserver;
use App\Observers\UserObserver;
use App\User;
use App\UserMeta;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Modules\Appointment\Entities\Appointment;
use Modules\Appointment\Entities\AppointmentException;
use Modules\Appointment\Entities\AppointmentPart;
use Modules\Appointment\Entities\AppointmentSms;
use Modules\Appointment\Entities\AppointmentUser;
use Spatie\Permission\Models\Role;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        User::observe(UserObserver::class);
        UserMeta::observe(GenericAdminModelObserver::class);
        Role::observe(GenericAdminModelObserver::class);
        Appointment::observe(GenericAdminModelObserver::class);
        AppointmentException::observe(GenericAdminModelObserver::class);
        AppointmentPart::observe(GenericAdminModelObserver::class);
        AppointmentSms::observe(GenericAdminModelObserver::class);
        AppointmentUser::observe(AppointmentUserObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }
}

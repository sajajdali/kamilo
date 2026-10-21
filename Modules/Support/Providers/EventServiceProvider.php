<?php

namespace Modules\Support\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Modules\Support\Events\AddNewTicketAnswer' => [
            'Modules\Support\Listeners\NotifyStudentOfNewTicketAnswer',
        ],
    ];
}

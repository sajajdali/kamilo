<?php

namespace Modules\Options\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Options\Http\Hooks;

class onloadServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $hooks = new Hooks();
        $hooks->Actions();
        $hooks->Filters();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}

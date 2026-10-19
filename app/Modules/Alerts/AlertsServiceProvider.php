<?php

namespace App\Modules\Alerts;

use App\Modules\Contracts\ModuleServiceProvider;

class AlertsServiceProvider extends ModuleServiceProvider
{
    protected string $moduleKey = 'alerts';

    protected string $moduleName = 'Alerts';

    public function register(): void
    {
        // Register services and dependencies...
    }

    public function boot(): void
    {
        parent::boot();
    }
}

<?php

namespace App\Modules\Pipelines;

use App\Modules\Contracts\ModuleServiceProvider;

class PipelinesServiceProvider extends ModuleServiceProvider
{
    protected string $moduleKey = 'pipelines';
    
    protected string $moduleName = 'Sales Pipeline';

    protected array $dependencies = ['contacts'];

    public function boot(): void
    {
        parent::boot();
    }
}

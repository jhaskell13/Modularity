<?php

namespace App\Modules\Pipelines;

use App\Modules\Pipelines\Filament\Resources\DealResource;
use App\Modules\Pipelines\Filament\Resources\PipelineResource;
use App\Modules\Contracts\ModuleServiceProvider;
use Filament\Facades\Filament;

class PipelinesServiceProvider extends ModuleServiceProvider
{
    protected string $moduleKey = 'pipelines';
    protected string $moduleName = 'Sales Pipeline';
    protected array $dependencies = ['contacts'];

    public function boot(): void
    {
        parent::boot();

        if (!$this->isModuleActive()) {
            return;
        }

        $this->registerFilamentResources();
    }

    protected function registerFilamentResources(): void
    {
        Filament::registerResources([
            PipelineResource::class,
            DealResource::class,
        ]);
    }
}

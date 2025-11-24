<?php

namespace App\Modules\Contacts;

use App\Modules\Contracts\ModuleServiceProvider;
use Filament\Facades\Filament;
use App\Modules\Contacts\Filament\Resources\ContactResource;
// use App\Modules\Contacts\Filament\Resources\CompanyResource;

class ContactsServiceProvider extends ModuleServiceProvider
{
    protected string $moduleKey = 'contacts';
    protected string $moduleName = 'Contact Management';
    protected array $dependencies = [];

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
        Filament::registerPages([
            // Pages here if needed
        ]);

        Filament::registerResources([
            ContactResource::class,
            // CompanyResource::class,
        ]);
    }
}

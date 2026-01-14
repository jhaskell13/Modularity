<?php

namespace App\Modules\Contacts;

use App\Modules\Contracts\ModuleServiceProvider;

class ContactsServiceProvider extends ModuleServiceProvider
{
    protected string $moduleKey = 'contacts';

    protected string $moduleName = 'Contact Management';

    public function boot(): void
    {
        parent::boot();
    }
}

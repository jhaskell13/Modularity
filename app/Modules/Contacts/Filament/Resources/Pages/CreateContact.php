<?php

namespace App\Modules\Contacts\Filament\Resources\Pages;

use App\Modules\Contacts\Filament\Resources\ContactResource;
use Filament\Resources\Pages\CreateRecord;

class CreateContact extends CreateRecord
{
    protected static string $resource = ContactResource::class;
}

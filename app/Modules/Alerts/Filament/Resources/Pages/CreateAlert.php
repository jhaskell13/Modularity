<?php

namespace App\Modules\Alerts\Filament\Resources\Pages;

use App\Modules\Alerts\Filament\Resources\AlertResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAlert extends CreateRecord
{
    protected static string $resource = AlertResource::class;
}

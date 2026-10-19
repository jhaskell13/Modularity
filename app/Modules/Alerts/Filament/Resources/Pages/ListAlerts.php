<?php

namespace App\Modules\Alerts\Filament\Resources\Pages;

use App\Modules\Alerts\Filament\Resources\AlertResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAlerts extends ListRecords
{
    protected static string $resource = AlertResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

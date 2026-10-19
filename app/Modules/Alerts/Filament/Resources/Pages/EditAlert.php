<?php

namespace App\Modules\Alerts\Filament\Resources\Pages;

use App\Modules\Alerts\Filament\Resources\AlertResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAlert extends EditRecord
{
    protected static string $resource = AlertResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

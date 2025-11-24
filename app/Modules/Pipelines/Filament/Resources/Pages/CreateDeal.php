<?php

namespace App\Modules\Pipelines\Filament\Resources\Pages;

use App\Modules\Pipelines\Filament\Resources\DealResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateDeal extends CreateRecord
{
    protected static string $resource = DealResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Set default owner to current user if not set
        if (!isset($data['owner_id'])) {
            $data['owner_id'] = Auth::id();
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

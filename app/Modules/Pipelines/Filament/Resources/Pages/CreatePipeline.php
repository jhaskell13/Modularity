<?php

namespace App\Modules\Pipelines\Filament\Resources\Pages;

use App\Modules\Pipelines\Filament\Resources\PipelineResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePipeline extends CreateRecord
{
    protected static string $resource = PipelineResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

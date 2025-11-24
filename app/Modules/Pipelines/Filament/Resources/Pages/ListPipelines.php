<?php

namespace App\Modules\Pipelines\Filament\Resources\Pages;

use App\Modules\Pipelines\Filament\Resources\PipelineResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPipelines extends ListRecords
{
    protected static string $resource = PipelineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

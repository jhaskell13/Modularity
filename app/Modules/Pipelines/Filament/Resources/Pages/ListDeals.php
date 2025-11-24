<?php

namespace App\Modules\Pipelines\Filament\Resources\Pages;

use App\Modules\Pipelines\Filament\Resources\DealResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListDeals extends ListRecords
{
    protected static string $resource = DealResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Deals'),

            'open' => Tab::make('Open')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'open'))
                ->badge(fn () => $this->getModel()::where('status', 'open')->count()),

            'won' => Tab::make('Won')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'won'))
                ->badge(fn () => $this->getModel()::where('status', 'won')->count())
                ->badgeColor('success'),

            'lost' => Tab::make('Lost')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'lost'))
                ->badge(fn () => $this->getModel()::where('status', 'lost')->count())
                ->badgeColor('danger'),
        ];
    }
}

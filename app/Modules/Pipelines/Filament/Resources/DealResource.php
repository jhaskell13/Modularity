<?php

namespace App\Modules\Pipelines\Filament\Resources;

use App\Modules\Pipelines\Filament\Resources\Pages\CreateDeal;
use App\Modules\Pipelines\Filament\Resources\Pages\EditDeal;
use App\Modules\Pipelines\Filament\Resources\Pages\ListDeals;
use App\Modules\Pipelines\Filament\Resources\Schemas\DealForm;
use App\Modules\Pipelines\Filament\Resources\Tables\DealsTable;
use App\Modules\Pipelines\Models\Deal;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DealResource extends Resource
{
    protected static ?string $model = Deal::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return DealForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DealsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDeals::route('/'),
            'create' => CreateDeal::route('/create'),
            'edit' => EditDeal::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['stage', 'pipeline', 'contact', 'company', 'owner']);
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}

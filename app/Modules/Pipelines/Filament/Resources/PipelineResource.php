<?php

namespace App\Modules\Pipelines\Filament\Resources;

use App\Modules\Pipelines\Filament\Resources\Pages\CreatePipeline;
use App\Modules\Pipelines\Filament\Resources\Pages\EditPipeline;
use App\Modules\Pipelines\Filament\Resources\Pages\ListPipelines;
use App\Modules\Pipelines\Filament\Resources\Schemas\PipelineForm;
use App\Modules\Pipelines\Filament\Resources\Tables\PipelinesTable;
use App\Modules\Pipelines\Models\Pipeline;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PipelineResource extends Resource
{
    protected static ?string $model = Pipeline::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return PipelineForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PipelinesTable::configure($table);
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
            'index' => ListPipelines::route('/'),
            'create' => CreatePipeline::route('/create'),
            'edit' => EditPipeline::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}

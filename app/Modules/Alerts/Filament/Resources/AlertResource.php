<?php

namespace App\Modules\Alerts\Filament\Resources;

use App\Modules\Alerts\Models\Alert;
use App\Modules\Alerts\Filament\Resources\Pages\CreateAlert;
use App\Modules\Alerts\Filament\Resources\Pages\EditAlert;
use App\Modules\Alerts\Filament\Resources\Pages\ListAlerts;
use App\Modules\Alerts\Filament\Resources\Schemas\AlertForm;
use App\Modules\Alerts\Filament\Resources\Tables\AlertsTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class AlertResource extends Resource
{
    protected static ?string $model = Alert::class;

    protected static string|UnitEnum|null $navigationGroup = 'System';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Alerts';

    public static function form(Schema $schema): Schema
    {
        return AlertForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AlertsTable::configure($table);
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
            'index' => ListAlerts::route('/'),
            'create' => CreateAlert::route('/create'),
            'edit' => EditAlert::route('/{record}/edit'),
        ];
    }
}

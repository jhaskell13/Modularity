<?php

namespace App\Modules\Alerts\Filament\Resources\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Forms;

class AlertForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                 Section::make()
                ->schema([
                    Forms\Components\TextInput::make('type')
                        ->required()
                        ->maxLength(255),
                ])
            ]);
    }
}

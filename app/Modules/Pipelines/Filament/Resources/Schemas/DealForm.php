<?php

namespace App\Modules\Pipelines\Filament\Resources\Schemas;

use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class DealForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Deal Information')
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('description')
                        ->rows(3)
                        ->columnSpanFull(),

                    Forms\Components\Select::make('pipeline_id')
                        ->relationship('pipeline', 'name')
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(fn ($state, callable $set) => $set('stage_id', null)),

                    Forms\Components\Select::make('stage_id')
                        ->relationship(
                            'stage',
                            'name',
                            fn (Builder $query, callable $get) => $query->where('pipeline_id', $get('pipeline_id'))
                        )
                        ->required()
                        ->preload(),

                    Forms\Components\TextInput::make('value')
                        ->numeric()
                        ->required()
                        ->prefix('$')
                        ->default(0),

                    Forms\Components\Select::make('currency')
                        ->options([
                            'USD' => 'USD ($)',
                            'EUR' => 'EUR (€)',
                            'GBP' => 'GBP (£)',
                            'CAD' => 'CAD (C$)',
                        ])
                        ->default('USD')
                        ->required(),

                    Forms\Components\TextInput::make('probability')
                        ->numeric()
                        ->suffix('%')
                        ->minValue(0)
                        ->maxValue(100)
                        ->default(50),
                ])->columns(2),

                Section::make('Contact Information')
                ->schema([
                    Forms\Components\Select::make('company_id')
                        ->relationship('company', 'name')
                        ->searchable()
                        ->preload()
                        ->createOptionForm([
                            Forms\Components\TextInput::make('name')->required(),
                            Forms\Components\TextInput::make('email')->email(),
                            Forms\Components\TextInput::make('phone')->tel(),
                        ]),

                    Forms\Components\Select::make('contact_id')
                        ->relationship('contact', 'first_name')
                        ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->first_name} {$record->last_name}")
                        ->searchable(['first_name', 'last_name', 'email'])
                        ->preload()
                        ->createOptionForm([
                            Forms\Components\TextInput::make('first_name')->required(),
                            Forms\Components\TextInput::make('last_name')->required(),
                            Forms\Components\TextInput::make('email')->email(),
                        ]),
                ])->columns(2),

                Section::make('Additional Details')
                ->schema([
                    Forms\Components\Select::make('owner_id')
                        ->relationship('owner', 'name')
                        ->searchable()
                        ->preload()
                        ->default(Auth::id())
                        ->required(),

                    Forms\Components\DatePicker::make('expected_close_date')
                        ->label('Expected Close Date')
                        ->default(now()->addDays(30)),

                    Forms\Components\Select::make('status')
                        ->options([
                            'open' => 'Open',
                            'won' => 'Won',
                            'lost' => 'Lost',
                        ])
                        ->default('open')
                        ->required(),

                    Forms\Components\TextInput::make('lost_reason')
                        ->maxLength(255)
                        ->hidden(fn (callable $get) => $get('status') !== 'lost'),
                ])->columns(2),
            ]);
    }
}

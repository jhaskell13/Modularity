<?php

namespace App\Modules\Pipelines\Filament\Resources\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components;
use Filament\Schemas\Components\Section;

class PipelineForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                ->schema([
                    Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255),

                    Components\Textarea::make('description')
                        ->rows(3)
                        ->columnSpanFull(),

                    Components\Toggle::make('is_default')
                        ->label('Default Pipeline')
                        ->helperText('New deals will use this pipeline by default'),

                    Components\Toggle::make('is_active')
                        ->label('Active')
                        ->default(true),

                    Components\TextInput::make('order')
                        ->numeric()
                        ->default(0)
                        ->helperText('Display order (lower numbers show first)'),
                ])->columns(2),

            Section::make('Stages')
                ->schema([
                    Components\Repeater::make('stages')
                        ->relationship()
                        ->schema([
                            Components\TextInput::make('name')
                                ->required()
                                ->maxLength(255),

                            Components\TextInput::make('probability')
                                ->numeric()
                                ->suffix('%')
                                ->minValue(0)
                                ->maxValue(100)
                                ->default(50),

                            Components\ColorPicker::make('color')
                                ->default('#3b82f6'),

                            Components\Toggle::make('is_closed')
                                ->label('Closed Stage')
                                ->helperText('Mark as a final/closed stage'),
                        ])
                        ->columns(4)
                        ->reorderable()
                        ->collapsible()
                        ->defaultItems(0)
                        ->addActionLabel('Add Stage'),
                ])
                ->collapsed(false),
            ]);
    }
}

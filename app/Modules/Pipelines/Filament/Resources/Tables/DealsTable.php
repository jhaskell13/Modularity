<?php

namespace App\Modules\Pipelines\Filament\Resources\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Table;
use Filament\Tables;
use App\Modules\Pipelines\Models\Deal;
use Filament\Forms\Components\TextArea;

class DealsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('value')
                    ->money(fn ($record) => $record->currency)
                    ->sortable()
                    ->weight('bold')
                    ->color('success'),

                Tables\Columns\TextColumn::make('stage.name')
                    ->badge()
                    ->color(fn ($record) => match($record->stage?->name) {
                        'Lead' => 'gray',
                        'Qualified' => 'info',
                        'Proposal' => 'warning',
                        'Negotiation' => 'primary',
                        'Closed Won' => 'success',
                        default => 'secondary',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('contact.first_name')
                    ->label('Contact')
                    ->formatStateUsing(fn ($record) => $record->contact
                        ? "{$record->contact->first_name} {$record->contact->last_name}"
                        : '-'
                    )
                    ->searchable(['first_name', 'last_name']),

                Tables\Columns\TextColumn::make('company.name')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('owner.name')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('probability')
                    ->suffix('%')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('expected_close_date')
                    ->date()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string  => match ($state) {
                        'open' => 'secondary',
                        'won' => 'success',
                        'lost' => 'danger',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('pipeline')
                    ->relationship('pipeline', 'name'),

                Tables\Filters\SelectFilter::make('stage')
                    ->relationship('stage', 'name'),

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'open' => 'Open',
                        'won' => 'Won',
                        'lost' => 'Lost',
                    ]),

                Tables\Filters\SelectFilter::make('owner')
                    ->relationship('owner', 'name'),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
                    Action::make('mark_won')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(fn (Deal $record) => $record->markAsWon())
                        ->visible(fn (Deal $record) => $record->isOpen()),
                    Action::make('mark_lost')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->schema([
                            Textarea::make('reason')
                                ->label('Lost Reason')
                                ->required(),
                        ])
                        ->action(function (Deal $record, array $data) {
                            $record->markAsLost($data['reason']);
                        })
                        ->visible(fn (Deal $record) => $record->isOpen()),
                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}

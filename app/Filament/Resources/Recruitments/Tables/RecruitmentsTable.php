<?php

declare(strict_types=1);

namespace App\Filament\Resources\Recruitments\Tables;

use App\Models\Recruitment;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class RecruitmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('start_date')
                    ->label(__('recruitments.start_date'))
                    ->date()
                    ->sortable(),
                TextColumn::make('end_date')
                    ->label(__('recruitments.end_date'))
                    ->date()
                    ->sortable(),
                TextColumn::make('slots')
                    ->label(__('recruitments.slots'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('major.name')
                    ->label(trans_choice('Major', 1))
                    ->formatStateUsing(fn(Recruitment $recruitment): string => "{$recruitment->major->name} ({$recruitment->major->load('studyMode')->studyMode->name})")
                    ->sortable()
                    ->searchable(),
                TextColumn::make('academicYear.start_year')
                    ->label(trans_choice('Academic year', 1))
                    ->badge()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('cost.price')
                    ->label(__('Price'))
                    ->money('PLN')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('status')
                    ->formatStateUsing(fn(Recruitment $recruitment) => $recruitment->status->label()),
                TextColumn::make('created_at')
                    ->label(Str::ucfirst(__('validation.attributes.created_at')))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(Str::ucfirst(__('validation.attributes.updated_at')))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('cost.price')
                    ->label(__('Price'))
                    ->relationship('cost', 'price'),
                TernaryFilter::make('is_suspended')
                    ->label(__('recruitments.is_suspended')),
            ])
            ->recordActions([
                EditAction::make(),
                ActionGroup::make([
                    ViewAction::make(),
                    Action::make('resume')
                        ->label(__('recruitments.resume'))
                        ->icon(Heroicon::OutlinedPlayCircle)
                        ->hidden(fn(Recruitment $recruitment): bool => ! $recruitment->is_suspended || $recruitment->end_date < now())
                        ->action(fn(Recruitment $recruitment): bool => $recruitment->update(['is_suspended' => false])),
                    Action::make('suspend')
                        ->label(__('recruitments.suspend'))
                        ->icon(Heroicon::OutlinedPauseCircle)
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalDescription(trans_choice('recruitments.suspend_confirmation', 1))
                        ->hidden(fn(Recruitment $recruitment): bool => $recruitment->is_suspended || $recruitment->end_date < now())
                        ->action(fn(Recruitment $recruitment): bool => $recruitment->update(['is_suspended' => true])),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('resume')
                        ->label(__('recruitments.resume_selected'))
                        ->icon(Heroicon::OutlinedPlayCircle)
                        ->requiresConfirmation()
                        ->action(function (Collection $records): void {
                            $records->each->update(['is_suspended' => false]);
                        }),
                    BulkAction::make('suspend')
                        ->label(__('recruitments.suspend_selected'))
                        ->icon(Heroicon::OutlinedPauseCircle)
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalDescription(trans_choice('recruitments.suspend_confirmation', 2))
                        ->action(function (Collection $records): void {
                            foreach ($records as $record) {
                                if ($record->end_date >= now()) {
                                    $record->update(['is_suspended' => true]);
                                }
                            }
                        }),
                ]),
            ]);
    }
}

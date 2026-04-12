<?php

declare(strict_types=1);

namespace App\Filament\Resources\Majors\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MajorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nazwa')
                    ->searchable(),
                TextColumn::make('semesters')
                    ->label('Semestry')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('studyLevel.name')
                    ->label('Poziom studiów')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('studyMode.name')
                    ->label('Tryb studiów')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('degreeTitle.name')
                    ->label('Tytuł')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([

            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

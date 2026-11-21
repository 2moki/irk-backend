<?php

declare(strict_types=1);

namespace App\Filament\Resources\Candidates;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ApplicationsRelationManager extends RelationManager
{
    protected static string $relationship = 'applications';

    protected static ?string $title = 'Aplikacje na kierunki';
    public function table(Table $table): Table
    {
        return $table
            ->columns([
                // 1. Identyfikator aplikacji
                TextColumn::make('id')
                    ->label('Id'),

                // 2. Kierunek (Pobierany przez relację: Application -> Recruitments -> Major)
                TextColumn::make('recruitments.major.name')
                    ->label('Kierunek')
                    ->searchable()
                    ->placeholder('-'),

                // 3. Status aplikacji (Pobierany z tabeli pivot)
                TextColumn::make('recruitmentApplications.application_status')
                    ->label('Status aplikacji')
                    ->badge()
                    ->placeholder('-'),

                // 4. Data płatności (Pobierana z tabeli pivot)
                TextColumn::make('recruitmentApplications.payment_date')
                    ->label('Data płatności')
                    ->date('d.m.Y') // W castach masz 'immutable_date', więc formatujemy jako czystą datę
                    ->placeholder('-'),

                // 5. Utworzono (Główna data utworzenia wniosku)
                TextColumn::make('created_at')
                    ->label('Utworzono')
                    ->dateTime('d.m.Y H:i'),
            ]);
    }

}

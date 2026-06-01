<?php

declare(strict_types=1);

namespace App\Filament\Exports;

use App\Models\Pivots\RecruitmentApplication;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class RecruitmentApplicationExport extends Exporter
{
    protected static ?string $model = RecruitmentApplication::class;

    public static function getColumns(): array
    {
        return [
            // --- DANE OSOBOWE ---
            ExportColumn::make('application.user.first_name')->label('Imię'),
            ExportColumn::make('application.user.middle_name')->label('Drugie imię'),
            ExportColumn::make('application.user.last_name')->label('Nazwisko'),
            ExportColumn::make('application.user.email')->label('Email'),
            ExportColumn::make('application.user.phone_prefix')->label('Prefix tel.'),
            ExportColumn::make('application.user.phone_number')->label('Numer tel.'),

            // --- TOŻSAMOŚĆ ---
            ExportColumn::make('application.user.pesel')->label('PESEL'),
            ExportColumn::make('application.user.document_number')->label('Nr dokumentu'),
            ExportColumn::make('application.user.date_of_birth')
                ->label('Data urodzenia')
                ->formatStateUsing(function ($state) {
                    if (! $state) {
                        return '—';
                    }
                    return $state instanceof \Carbon\Carbon ? $state->format('Y-m-d') : (string) $state;
                }),
            ExportColumn::make('application.user.gender')
                ->label('Płeć')
                ->formatStateUsing(fn($state) => is_object($state) && method_exists($state, 'getLabel') ? $state->getLabel() : ($state ?? '—')),

            // --- ADRES ZAMIESZKANIA ---
            ExportColumn::make('application.address.country.name_pl')->label('Kraj'),
            ExportColumn::make('application.address.voivodeship.name_pl')->label('Województwo'),
            ExportColumn::make('application.address.city')->label('Miasto'),
            ExportColumn::make('application.address.street')->label('Ulica'),
            ExportColumn::make('application.address.house_number')->label('Nr domu'),
            ExportColumn::make('application.address.apartment_number')->label('Nr lokalu'),
            ExportColumn::make('application.address.post_code')->label('Kod pocztowy'),

            // --- INFORMACJE O REKRUTACJI ---
            ExportColumn::make('recruitment.major.name')->label('Kierunek'),
            ExportColumn::make('recruitment.major.studyLevel.name')->label('Stopień studiów'),
            ExportColumn::make('recruitment.major.studyMode.name')->label('Forma studiów'),

            // POPRAWIONA LINIA: Wymuszenie rzutowania na (float)
            ExportColumn::make('got_points')
                ->label('Punkty')
                ->formatStateUsing(fn($state) => number_format((float) ($state ?? 0), 2, ',', ' ')),

            ExportColumn::make('is_paid')
                ->label('Opłacono')
                ->formatStateUsing(fn($state) => $state ? 'Tak' : 'Nie'),

            ExportColumn::make('payment_date')
                ->label('Data opłaty')
                ->formatStateUsing(function ($state) {
                    if (! $state) {
                        return '—';
                    }
                    return $state instanceof \Carbon\Carbon ? $state->format('Y-m-d') : (string) $state;
                }),

            ExportColumn::make('application_status')
                ->label('Status aplikacji')
                ->formatStateUsing(fn($state) => is_object($state) && method_exists($state, 'getLabel') ? $state->getLabel() : ($state ?? '—')),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        return "Eksport kandydatów do CSV został ukończony. Liczba wyeksportowanych rekordów: {$export->successful_rows}";
    }

    public function getFileName(Export $export): string
    {
        return "recruitment_applications_{$export->getKey()}";
    }

    public function getFileDisk(): string
    {
        return 'local';
    }
}

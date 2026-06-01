<?php

declare(strict_types=1);

namespace App\Filament\Resources\Candidates;

use App\Filament\Resources\Candidates\Pages\ListCandidates;
use App\Filament\Resources\Candidates\Pages\ViewCandidates;
use App\Filament\Resources\Candidates\RelationManagers\ApplicationsRelationManager;
use App\Models\User;
use BackedEnum;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
// Oficjalne importy dla architektury Filament v5 Schema
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CandidatesResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user';

    protected static ?string $label = 'Kandydat';
    protected static ?string $pluralLabel = 'Kandydaci';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->candidates()
            ->with(['address', 'applications'])
            ->withCount('applications');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('first_name')->label('Imię')->searchable(),
                TextColumn::make('last_name')->label('Nazwisko')->searchable(),
                TextColumn::make('email')->label('Email')->searchable(),

                TextColumn::make('applications_count')
                    ->label('Aplikacje')
                    ->badge(),

                TextColumn::make('address.city')
                    ->label('Miasto')
                    ->placeholder('-'),

                TextColumn::make('created_at')
                    ->label('Utworzono')
                    ->dateTime('d.m.Y H:i'),
            ])
            ->actions([
                ViewAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                // Sekcja 1: Dane identyfikacyjne
                Section::make('Dane identyfikacyjne')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('first_name')->label('Imię')->disabled(),
                            TextInput::make('middle_name')->label('Drugie imię')->disabled(),
                            TextInput::make('last_name')->label('Nazwisko')->disabled(),
                        ]),
                        Grid::make(3)->schema([
                            TextInput::make('pesel')->label('Numer PESEL')->disabled(),
                            TextInput::make('document_number')->label('Numer dowodu/paszportu')->disabled(),

                            // POPRAWIONE: Bezpieczne formatowanie daty urodzenia z tekstu
                            TextInput::make('date_of_birth')
                                ->label('Data urodzenia')
                                ->disabled()
                                ->formatStateUsing(fn($state) => $state ? date('d.m.Y', strtotime($state)) : '-'),
                        ]),
                    ]),

                // Sekcja 2: Kontakt (Zoptymalizowana pod długie adresy e-mail)
                Section::make('Kontakt')
                    ->schema([
                        // E-mail dostaje osobną przestrzeń, żeby się nie ucinał
                        TextInput::make('email')
                            ->label('Adres E-mail')
                            ->disabled(),

                        // Prefiks i telefon lądują obok siebie w osobnym rzędzie
                        Grid::make(3)->schema([
                            TextInput::make('phone_prefix')
                                ->label('Prefiks tel.')
                                ->disabled(),

                            TextInput::make('phone_number')
                                ->label('Numer telefonu')
                                ->columnSpan(2) // Numer telefonu dostaje 2 z 3 kolumn
                                ->disabled(),
                        ]),
                    ]),

                // ================= POPRAWIONA SEKCJA 3 =================
                // Używamy komponentu Group z powiązaniem do relacji 'address'
                Section::make('Adres zamieszkania')
                    ->schema([
                        Group::make()
                            ->relationship('address') // Przełącza kontekst na model Address
                            ->schema([
                                // Zostawiamy Grid(3), ale wrzucamy do niego 6 pól, co automatycznie utworzy 2 równe rzędy po 3 pola
                                Grid::make(3)->schema([
                                    // Rząd 1: Rozbita ulica, nr domu i nr lokalu
                                    TextInput::make('street')
                                        ->label('Ulica')
                                        ->disabled(),

                                    TextInput::make('house_number')
                                        ->label('Numer domu')
                                        ->disabled(),

                                    TextInput::make('apartment_number')
                                        ->label('Numer lokalu')
                                        ->disabled(),

                                    // Rząd 2: Kod, Miejscowość i dodatkowe 2 pola z Twojego modelu (Poczta i Województwo)
                                    TextInput::make('post_code')
                                        ->label('Kod pocztowy')
                                        ->disabled(),

                                    TextInput::make('city')
                                        ->label('Miejscowość')
                                        ->disabled(),

                                    TextInput::make('post_office')
                                        ->label('Poczta')
                                        ->disabled(),
                                ]),
                            ]),
                    ]),
                // Sekcja 5: Statystyki
                Section::make('Statystyki systemowe')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('applications_count')->label('Liczba wybranych kierunków (aplikacji)')->disabled(),

                            // POPRAWIONE: Bezpieczne formatowanie daty i czasu utworzenia konta
                            TextInput::make('created_at')
                                ->label('Data założenia konta')
                                ->disabled()
                                ->formatStateUsing(fn($state) => $state ? date('d.m.Y H:i', strtotime($state)) : '-'),
                        ]),
                    ]),
            ]);
    }
    public static function getRelations(): array
    {
        return [
            // Podpięcie Relation Managera pod spód widoku kandydata
            ApplicationsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCandidates::route('/'),
            'view' => ViewCandidates::route('/{record}'),
        ];
    }
}

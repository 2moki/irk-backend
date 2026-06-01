<?php

namespace App\Filament\Resources\Candidates;

use App\Filament\Resources\Candidates\Pages\ListCandidates;
use App\Filament\Resources\Candidates\Pages\ViewCandidates;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\ViewAction;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput; 
use Filament\Schemas\Components\Grid;     
use Filament\Schemas\Components\Section;  
use Illuminate\Database\Eloquent\Builder;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;

class CandidatesResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user';

    protected static ?string $label = 'Kandydat';
    protected static ?string $pluralLabel = 'Kandydaci';

    public static function getEloquentQuery(): Builder
    {
        // Wykorzystujemy Twój lokalny scope zamiast ->role() i dołączamy relację szczegółów
        return parent::getEloquentQuery()
            ->candidates()
            ->with(['candidateDetail'])
            ->withCount('applications');
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
                        TextInput::make('date_of_birth')->label('Data urodzenia')->disabled(),
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
                Grid::make(3)->schema([
                    TextInput::make('street')
                        ->label('Ulica i numer')
                        ->disabled(),
                        
                    TextInput::make('post_code')
                        ->label('Kod pocztowy')
                        ->disabled(),
                        
                    TextInput::make('city')
                        ->label('Miejscowość')
                        ->disabled(),
                ]),
            ]),
    ]),

            // Sekcja 5: Statystyki
            Section::make('Statystyki systemowe')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('applications_count')->label('Liczba wybranych kierunków (aplikacji)')->disabled(),
                        TextInput::make('created_at')->label('Data założenia konto IRK')->disabled(),
                    ]),
                ]),
        ]);
}

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('first_name')
                    ->label('Imię')
                    ->searchable(),
                TextColumn::make('last_name')
                    ->label('Nazwisko')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('applications_count')
                    ->badge()
                    ->label('Liczba aplikacji'),
                TextColumn::make('created_at')
                    ->date()
                    ->label('Utworzono'),
            ])
            ->actions([
                ViewAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCandidates::route('/'),
            'view' => ViewCandidates::route('/{record}'),
        ];
    }
}
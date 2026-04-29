<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\Gender;
use App\Models\Country;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // --- DANE LOGOWANIA ---
                TextInput::make('first_name')
                    ->label('Imię')
                    ->required(),

                TextInput::make('middle_name')
                    ->label('Drugie imię'),

                TextInput::make('last_name')
                    ->label('Nazwisko')
                    ->required(),

                TextInput::make('email')
                    ->label('Adres email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true),

                TextInput::make('password')
                    ->label('Hasło')
                    ->password()
                    ->required()
                    ->visibleOn('create'),


                // --- TELEFON I TOŻSAMOŚĆ ---
                TextInput::make('phone_prefix')
                    ->label('Prefix')
                    ->tel()
                    ->required()
                    ->default('+48'),

                TextInput::make('phone_number')
                    ->label('Numer telefonu')
                    ->tel()
                    ->required(),

                TextInput::make('pesel')
                    ->label('Numer PESEL')
                    ->maxLength(11)
                    ->required(fn($get) => ! $get('no_pesel'))
                    ->hidden(fn($get) => $get('no_pesel')),

                TextInput::make('document_number')
                    ->label('Numer dokumentu')
                    ->required(fn($get) => $get('no_pesel'))
                    ->visible(fn($get) => $get('no_pesel')),

                Checkbox::make('no_pesel')
                    ->label('Nie posiadam numeru PESEL (obcokrajowiec)')
                    ->live()
                    ->dehydrated(false)
                    ->afterStateHydrated(function (Checkbox $component, $record): void {
                        if ($record && $record->pesel === null) {
                            $component->state(true);
                        }
                    }),

                DatePicker::make('date_of_birth')
                    ->label('Data urodzenia')
                    ->required(),

                Select::make('gender')
                    ->label('Płeć')
                    ->options(Gender::class)
                    ->default(Gender::NOT_SPECIFIED)
                    ->required()
                    ->native(false),

                // --- ADRES KORESPONDENCYJNY (DYNAMICZNY) ---

                Checkbox::make('has_different_mailing_address')
                    ->label('Adres korespondencyjny jest inny niż zamieszkania')
                    ->live() // Sprawia, że formularz reaguje natychmiast po kliknięciu
                    ->dehydrated(false)
                    ->afterStateHydrated(function (Checkbox $component, $record): void {
                        if ($record && $record->mailing_address_id !== null) {
                            $component->state(true);
                        }
                    }),



                \Filament\Schemas\Components\Section::make('Adres korespondencyjny')
                    ->relationship('mailingAddress') // Filament sam stworzy rekord w tabeli addresses
                    ->description('Wypełnij, jeśli dokumenty mają trafiać pod inny adres')
                    ->visible(fn($get) => $get('has_different_mailing_address')) // Sekcja pojawia się tylko gdy checkbox jest zaznaczony
                    ->schema([
                        Select::make('country_id')
                            ->label('Państwo')
                            ->relationship('country', 'name_pl') // Używamy name_pl, jak ustaliliśmy wcześniej
                            ->searchable()
                            ->preload()
                            ->live()
                            ->required(),

                        Select::make('voivodeship_id')
                            ->label('Województwo')
                            ->relationship('voivodeship', 'name_pl')
                            ->visible(function ($get) {
                                $polandId = Country::where('code', 'PL')->value('id');
                                return $get('country_id') === $polandId;
                            })
                            ->required(function ($get) {
                                $polandId = Country::where('code', 'PL')->value('id');
                                return $get('country_id') === $polandId;
                            }),

                        TextInput::make('state')
                            ->label('Stan / Region')
                            ->visible(function ($get) {
                                $polandId = Country::where('code', 'PL')->value('id');
                                return $get('country_id') !== $polandId && $get('country_id') !== null;
                            }),

                        TextInput::make('post_code')
                            ->label('Kod pocztowy')
                            ->required(),


                        TextInput::make('city')
                            ->label('Miasto')
                            ->required(),

                        TextInput::make('post_office')
                            ->label('Poczta'),

                        TextInput::make('street')
                            ->label('Ulica'),

                        TextInput::make('house_number')
                            ->label('Nr domu')
                            ->required(),

                        TextInput::make('apartment_number')
                            ->label('Nr lokalu'),
                    ])
                    ->columns(1),

                // Użycie nowej klasy Section z namespace'u Schemas
                \Filament\Schemas\Components\Section::make('Adres zamieszkania')
                    ->relationship('address')
                    ->schema([
                        Select::make('country_id')
                            ->label('Państwo')
                            ->relationship('country', 'name_pl')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->required(),

                        Select::make('voivodeship_id')
                            ->label('Województwo')
                            ->relationship('voivodeship', 'name_pl')
                            ->visible(function ($get) {
                                $polandId = Country::where('code', 'PL')->value('id');
                                return $get('country_id') === $polandId;
                            })
                            ->required(function ($get) {
                                $polandId = Country::where('code', 'PL')->value('id');
                                return $get('country_id') === $polandId;
                            }),

                        TextInput::make('state')
                            ->label('Stan / Region')
                            ->visible(function ($get) {
                                $polandId = Country::where('code', 'PL')->value('id');
                                return $get('country_id') !== $polandId && $get('country_id') !== null;
                            }),

                        TextInput::make('post_code')
                            ->label('Kod pocztowy')
                            ->required(),

                        TextInput::make('city')
                            ->label('Miasto')
                            ->required(),

                        TextInput::make('post_office')
                            ->label('Poczta'),

                        TextInput::make('street')
                            ->label('Ulica'),

                        TextInput::make('house_number')
                            ->label('Nr domu')
                            ->required(),

                        TextInput::make('apartment_number')
                            ->label('Nr lokalu'),
                    ])
                    ->columns(1),

            ]);
    }
}

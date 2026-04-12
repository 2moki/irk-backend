<?php

declare(strict_types=1);

namespace App\Filament\Resources\Majors\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MajorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('name')
                ->label('Nazwa')
                ->required()
                ->maxLength(255),

            TextInput::make('semesters')
                ->label('Semestry')
                ->numeric()
                ->required(),

            Select::make('study_level_id')
                ->label('Poziom studiów')
                ->relationship('studyLevel', 'name')
                ->searchable()
                ->preload()
                ->required(),

            Select::make('study_mode_id')
                ->label('Tryb studiów')
                ->relationship('studyMode', 'name')
                ->searchable()
                ->preload()
                ->required(),

            Select::make('degree_title_id')
                ->label('Tytuł')
                ->relationship('degreeTitle', 'name')
                ->searchable()
                ->preload()
                ->required(),
        ]);
    }
}

<?php

declare(strict_types=1);

namespace App\Filament\Resources\Qualifications\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class QualificationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nazwa kwalifikacji')
                    ->required()
                    ->maxLength(255),

                Select::make('qualification_category_id')
                    ->label('Kategoria')
                    ->relationship('QualificationCategory', 'name'),

            ]);

    }
}

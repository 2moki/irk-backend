<?php

declare(strict_types=1);

namespace App\Filament\Resources\Languages\Tables;

use App\Models\Language;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class LanguageSelectionTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(fn(): Builder => Language::query())
            ->columns([
                TextColumn::make('name')
                    ->label(Str::ucfirst(__('validation.attributes.name')))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('code')
                    ->label(Str::ucfirst(__('validation.attributes.code')))
                    ->badge()
                    ->sortable()
                    ->searchable(),
            ]);
    }
}

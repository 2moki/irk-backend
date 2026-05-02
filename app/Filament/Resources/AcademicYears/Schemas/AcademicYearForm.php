<?php

declare(strict_types=1);

namespace App\Filament\Resources\AcademicYears\Schemas;

use App\Enums\BillingType;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class AcademicYearForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('start_year')
                    ->label(__('academic_years.start_year'))
                    ->required()
                    ->options(function (): array {
                        $years = range(now()->addYears(5)->format('Y'), now()->subYears(5)->format('Y'));
                        return array_combine($years, $years);
                    })
                    ->searchable(),
                Select::make('billing_type')
                    ->label(__('billing_types.billing_type'))
                    ->options(BillingType::class)
                    ->selectablePlaceholder(false)
                    ->required(),
                Select::make('grade_mapping_id')
                    ->label(__('academic_years.grade_mapping'))
                    ->relationship('gradeMapping', 'name')
                    ->selectablePlaceholder(false)
                    ->required(),
            ]);
    }
}

<?php

declare(strict_types=1);

namespace App\Filament\Resources\Recruitments\Schemas;

use App\Filament\Resources\AcademicYears\Schemas\AcademicYearForm;
use App\Models\Major;
use App\Models\Recruitment;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;

class RecruitmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('start_date')
                    ->label(__('recruitments.start_date'))
                    ->native(false)
                    ->required(),
                DatePicker::make('end_date')
                    ->label(__('recruitments.end_date'))
                    ->native(false)
                    ->required()
                    ->after('start_date'),
                TextInput::make('slots')
                    ->label(__('recruitments.slots'))
                    ->required()
                    ->numeric()
                    ->minValue(1),
                Select::make('academic_year_id')
                    ->label(trans_choice('Academic year', 1))
                    ->relationship(
                        name: 'academicYear',
                        titleAttribute: 'start_year',
                        modifyQueryUsing: fn(Builder $query) => $query->limit(5),
                    )
                    ->createOptionForm(fn(Schema $schema): Schema => AcademicYearForm::configure($schema)->columns())
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('major_id')
                    ->label(trans_choice('Major', 1))
                    ->relationship(
                        name: 'major',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn(Builder $query) => $query->limit(5),
                    )
                    ->getOptionLabelFromRecordUsing(fn(Major $major) => "{$major->name} ({$major->load('studyMode')->studyMode->name})")
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('cost_id')
                    ->label(__('Price'))
                    ->native(false)
                    ->relationship('cost', 'price')
                    ->createOptionForm([
                        TextInput::make('price')
                            ->label(__('Price'))
                            ->required()
                            ->numeric()
                            ->unique(),
                    ])
                    ->prefixIcon(Heroicon::OutlinedCurrencyDollar)
                    ->suffix('zł')
                    ->required(),
                Toggle::make('is_suspended')
                    ->label(__('recruitments.is_suspended'))
                    ->hidden(fn(?Recruitment $recruitment) => isset($recruitment) && $recruitment->end_date < now()),
            ]);
    }
}

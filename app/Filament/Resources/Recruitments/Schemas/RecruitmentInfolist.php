<?php

declare(strict_types=1);

namespace App\Filament\Resources\Recruitments\Schemas;

use App\Models\Recruitment;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class RecruitmentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('status')
                    ->formatStateUsing(fn(Recruitment $recruitment) => $recruitment->status->label())
                    ->columnSpanFull(),
                TextEntry::make('start_date')
                    ->label(__('recruitments.start_date'))
                    ->date(),
                TextEntry::make('end_date')
                    ->label(__('recruitments.end_date'))
                    ->date(),
                TextEntry::make('slots')
                    ->label(__('recruitments.slots'))
                    ->numeric(),
                TextEntry::make('major')
                    ->label(trans_choice('Major', 1))
                    ->formatStateUsing(fn(Recruitment $recruitment): string => "{$recruitment->major->name} ({$recruitment->major->load('studyMode')->studyMode->name})"),
                TextEntry::make('academicYear.start_year')
                    ->label(trans_choice('Academic year', 1)),
                TextEntry::make('cost.price')
                    ->label(__('Price'))
                    ->money('PLN'),
                TextEntry::make('created_at')
                    ->label(Str::ucfirst(__('validation.attributes.created_at')))
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->label(Str::ucfirst(__('validation.attributes.updated_at')))
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}

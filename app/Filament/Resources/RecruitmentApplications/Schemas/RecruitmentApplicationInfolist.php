<?php

declare(strict_types=1);

namespace App\Filament\Resources\RecruitmentApplications\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class RecruitmentApplicationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('application.user.name')
                    ->label(trans_choice('Candidate', 1)),

                TextEntry::make('recruitment.major.detailed_name')
                    ->label(trans_choice('Major', 1)),

                TextEntry::make('recruitment.major.studyMode.name')
                    ->label(trans_choice('Study mode', 1)),

                TextEntry::make('priority')
                    ->label(__('applications.priority'))
                    ->numeric(),

                TextEntry::make('got_points')
                    ->label(__('applications.points'))
                    ->numeric(2),

                IconEntry::make('is_paid')
                    ->label(__('applications.is_paid'))
                    ->boolean(),

                TextEntry::make('payment_date')
                    ->label(__('applications.payment_date'))
                    ->date()
                    ->placeholder('-'),

                TextEntry::make('application_status')
                    ->label(trans_choice('Application status', 1))
                    ->badge(),

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

<?php

declare(strict_types=1);

namespace App\Filament\Resources\RecruitmentApplications\Tables;

use App\Enums\ApplicationStatus;
use App\Filament\Exports\RecruitmentApplicationExport;
use App\Models\Major;
use App\Models\Pivots\RecruitmentApplication;
use App\Models\Recruitment;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ExportBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class RecruitmentApplicationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('application.user.name')
                    ->label(trans_choice('Candidate', 1)),

                TextColumn::make('recruitment.major.detailed_name')
                    ->label(trans_choice('Major', 1)),

                TextColumn::make('recruitment.major.studyMode.name')
                    ->label(trans_choice('Study mode', 1))
                    ->sortable()
                    ->searchable(),

                TextColumn::make('got_points')
                    ->label(__('applications.points'))
                    ->numeric(2)
                    ->sortable(),

                IconColumn::make('is_paid')
                    ->label(__('applications.is_paid'))
                    ->boolean()
                    ->sortable(),

                TextColumn::make('payment_date')
                    ->label(__('applications.payment_date'))
                    ->date()
                    ->sortable(),

                TextColumn::make('application_status')
                    ->label(trans_choice('Application status', 1))
                    ->badge()
                    ->sortable()
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label(Str::ucfirst(__('validation.attributes.created_at')))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label(Str::ucfirst(__('validation.attributes.updated_at')))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('got_points', 'desc')
            ->defaultGroup('recruitment.major.name')
            ->groupingDirectionSettingHidden()
            ->groups([
                Group::make('recruitment.major.name')
                    ->label(trans_choice('Major', 1))
                    ->getTitleFromRecordUsing(function (RecruitmentApplication $recruitmentApplication): string {
                        $majorWithLevel = $recruitmentApplication->recruitment->major->detailed_name;
                        $studyMode = $recruitmentApplication->recruitment->major->studyMode->name;

                        return $majorWithLevel . ' - ' . $studyMode;
                    }),

                Group::make('recruitment.academicYear.start_year')
                    ->label(trans_choice('Academic year', 1))
                    ->orderQueryUsing(fn(Builder $query): Builder => $query->orderByDesc(
                        Recruitment::select('academic_years.start_year')
                            ->join('academic_years', 'academic_years.id', '=', 'recruitments.academic_year_id')
                            ->whereColumn('recruitments.id', 'recruitment_application.recruitment_id'),
                    )),
            ])
            ->filters([
                SelectFilter::make('start_year')
                    ->label(__('academic_years.start_year'))
                    ->relationship(
                        name: 'recruitment.academicYear',
                        titleAttribute: 'start_year',
                        modifyQueryUsing: fn(Builder $query): Builder => $query->orderByDesc('start_year')->limit(5),
                    )
                    ->searchable()
                    ->preload()
                    ->native(false),

                SelectFilter::make('major')
                    ->label(trans_choice('Major', 1))
                    ->relationship(
                        name: 'recruitment.major',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn(Builder $query): Builder => $query->with(['studyLevel', 'studyMode'])->limit(5),
                    )
                    ->getOptionLabelFromRecordUsing(fn(Major $major): string => "{$major->name} ({$major->studyLevel->name}) - {$major->studyMode->name}")
                    ->searchable()
                    ->preload()
                    ->native(false),

                SelectFilter::make('study_mode')
                    ->label(trans_choice('Study mode', 1))
                    ->relationship(
                        name: 'recruitment.major.studyMode',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn(Builder $query): Builder => $query->limit(5),
                    )
                    ->searchable()
                    ->preload()
                    ->native(false),

                SelectFilter::make('application_status')
                    ->label(trans_choice('Application status', 2))
                    ->options(ApplicationStatus::class)
                    ->multiple(),

                TernaryFilter::make('is_paid')
                    ->label(__('applications.is_paid'))
                    ->native(false),
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ExportBulkAction::make()
                        ->label('Eksportuj wybrane do CSV')
                        ->exporter(RecruitmentApplicationExport::class)
                        ->columnMapping(false),
                ]),

                ExportAction::make()
                    ->label('Eksportuj do CSV')
                    ->exporter(RecruitmentApplicationExport::class)
                    ->columnMapping(false),
            ]);
    }
}

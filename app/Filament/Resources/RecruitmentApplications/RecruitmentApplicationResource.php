<?php

declare(strict_types=1);

namespace App\Filament\Resources\RecruitmentApplications;

use App\Filament\Resources\RecruitmentApplications\Pages\ListRecruitmentApplications;
use App\Filament\Resources\RecruitmentApplications\Pages\ViewRecruitmentApplication;
use App\Filament\Resources\RecruitmentApplications\Schemas\RecruitmentApplicationInfolist;
use App\Filament\Resources\RecruitmentApplications\Tables\RecruitmentApplicationsTable;
use App\Models\Pivots\RecruitmentApplication;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RecruitmentApplicationResource extends Resource
{
    protected static ?string $model = RecruitmentApplication::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentDuplicate;

    public static function getModelLabel(): string
    {
        return trans_choice('Recruitment Application', 1);
    }

    public static function getPluralModelLabel(): string
    {
        return trans_choice('Recruitment Application', 2);
    }

    public static function infolist(Schema $schema): Schema
    {
        return RecruitmentApplicationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RecruitmentApplicationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [

        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with([
            'recruitment.major.studyLevel',
            'recruitment.major.studyMode',
            'application.user.address.country',
            'application.user.address.voivodeship',
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRecruitmentApplications::route('/'),
            'view' => ViewRecruitmentApplication::route('/{record}'),
        ];
    }
}

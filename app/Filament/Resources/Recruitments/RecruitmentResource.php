<?php

declare(strict_types=1);

namespace App\Filament\Resources\Recruitments;

use App\Filament\Resources\Recruitments\Pages\CreateRecruitment;
use App\Filament\Resources\Recruitments\Pages\EditRecruitment;
use App\Filament\Resources\Recruitments\Pages\ListRecruitments;
use App\Filament\Resources\Recruitments\Pages\ViewRecruitment;
use App\Filament\Resources\Recruitments\RelationManagers\RequirementGroupsRelationManager;
use App\Filament\Resources\Recruitments\Schemas\RecruitmentForm;
use App\Filament\Resources\Recruitments\Schemas\RecruitmentInfolist;
use App\Filament\Resources\Recruitments\Tables\RecruitmentsTable;
use App\Models\Recruitment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RecruitmentResource extends Resource
{
    protected static ?string $model = Recruitment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    public static function getLabel(): ?string
    {
        return trans_choice('Recruitment', 1);
    }

    public static function getPluralLabel(): ?string
    {
        return trans_choice('Recruitment', 2);
    }

    public static function form(Schema $schema): Schema
    {
        return RecruitmentForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return RecruitmentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RecruitmentsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RequirementGroupsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRecruitments::route('/'),
            'create' => CreateRecruitment::route('/create'),
            'view' => ViewRecruitment::route('/{record}'),
            'edit' => EditRecruitment::route('/{record}/edit'),
        ];
    }
}

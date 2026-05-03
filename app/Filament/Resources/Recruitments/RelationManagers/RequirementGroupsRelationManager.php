<?php

declare(strict_types=1);

namespace App\Filament\Resources\Recruitments\RelationManagers;

use App\Models\Qualification;
use App\Models\Recruitment;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;

class RequirementGroupsRelationManager extends RelationManager
{
    protected static string $relationship = 'requirementGroups';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getModelLabel(): ?string
    {
        return trans_choice('recruitments.requirement_group', 1);
    }

    public static function getPluralModelLabel(): ?string
    {
        return trans_choice('recruitments.requirement_group', 1);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(Str::ucfirst(__('validation.attributes.name')))
                    ->columnSpanFull()
                    ->unique(modifyRuleUsing: function (Unique $rule) {
                        /** @var Recruitment $recruitment */
                        $recruitment = $this->getOwnerRecord();

                        return $rule->where('recruitment_id', $recruitment->id);
                    }),
                TextInput::make('weight')
                    ->label(__('recruitments.group_weight'))
                    ->required()
                    ->numeric(),
                TextInput::make('qualifications_count')
                    ->label(__('recruitments.qualifications_count'))
                    ->required()
                    ->numeric(),
                Repeater::make('requirementGroupQualifications')
                    ->label(__('recruitments.requirement_group_qualifications'))
                    ->relationship()
                    ->table([
                        Repeater\TableColumn::make(trans_choice('Qualification', 1)),
                        Repeater\TableColumn::make(__('recruitments.qualification_weight')),
                    ])
                    ->schema([
                        Select::make('qualification_id')
                            ->relationship(
                                name: 'qualification',
                                modifyQueryUsing: fn(Builder $query) => $query->with(['qualificationCategory'])->latest(),
                            )
                            ->required()
                            ->distinct()
                            ->getOptionLabelFromRecordUsing(
                                fn(Qualification $record): string => "{$record->name} ({$record->qualificationCategory->name})",
                            )
                            ->optionsLimit(5)
                            ->preload()
                            ->searchable(),
                        TextInput::make('weight')
                            ->label(__('recruitments.group_weight'))
                            ->required()
                            ->numeric()
                            ->minValue(0),
                    ])
                    ->columns()
                    ->columnSpanFull()
                    ->required()
                    ->minItems(1)
                    ->defaultItems(1)
                    ->addActionLabel(__('actions.named.add', ['name' => Str::lower(trans_choice('Qualification', 2))]))
                    ->addActionAlignment(Alignment::Right),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading(trans_choice('recruitments.requirement_group', 2))
            ->columns([
                TextColumn::make('name')
                    ->label(Str::ucfirst(__('validation.attributes.name')))
                    ->sortable(),
                TextColumn::make('weight')
                    ->label(__('recruitments.group_weight'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('qualifications_count')
                    ->label(__('recruitments.qualifications_count'))
                    ->numeric()
                    ->sortable(),
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
            ->filters([

            ])
            ->headerActions([
                CreateAction::make()
                    ->mutateDataUsing(function (array $data): array {
                        if (empty($data['name'])) {
                            $data['name'] = Str::ucfirst(__('validation.attributes.group')) . '-' . now()->format('YmdHis');
                        }

                        return $data;
                    }),
                AssociateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DissociateAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }


}

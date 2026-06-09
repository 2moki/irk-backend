<?php

declare(strict_types=1);

namespace App\Filament\Resources\Candidates;

use App\Enums\DocumentStatus;
use App\Enums\DocumentType;
use App\Models\UserDocument;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;

class DocumentsRelationManager extends RelationManager
{
    protected static string $relationship = 'documents';

    protected static ?string $title = 'Dokumenty i zdjęcia';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('document_type')
                    ->label('Typ dokumentu')
                    ->badge()
                    ->formatStateUsing(fn (DocumentType $state): string => $state->label())
                    ->sortable(),

                TextColumn::make('file_name')
                    ->label('Nazwa pliku')
                    ->limit(40)
                    ->tooltip(fn (UserDocument $record): string => $record->file_name),

                TextColumn::make('document_status')
                    ->label('Status')
                    ->badge()
                    ->sortable(),

                TextColumn::make('rejection_reason')
                    ->label('Przyczyna odrzucenia')
                    ->limit(50)
                    ->placeholder('—')
                    ->tooltip(fn (UserDocument $record): ?string => $record->rejection_reason),

                TextColumn::make('created_at')
                    ->label('Przesłano')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                // --- Podgląd ---
                Action::make('preview')
                    ->label('Podgląd')
                    ->icon('heroicon-o-eye')
                    ->modalHeading(fn (UserDocument $record): string => "Podgląd: {$record->file_name}")
                    ->modalContent(function (UserDocument $record): HtmlString {
                        $url = Storage::disk('public')->url($record->file_path);
                        $isImage = in_array(
                            strtolower(pathinfo($record->file_name, PATHINFO_EXTENSION)),
                            ['jpg', 'jpeg', 'png', 'gif', 'webp'],
                        );

                        if ($isImage) {
                            return new HtmlString(
                                "<div class='flex justify-center p-4'>"
                                . "<img src='{$url}' alt='{$record->file_name}' class='max-h-[70vh] max-w-full rounded-lg shadow-md' />"
                                . "</div>",
                            );
                        }

                        return new HtmlString(
                            "<div class='p-4'>"
                            . "<iframe src='{$url}' class='w-full h-[70vh] rounded-lg border' title='{$record->file_name}'></iframe>"
                            . "<p class='mt-2 text-sm text-gray-500 text-center'>"
                            . "<a href='{$url}' target='_blank' class='text-primary-600 underline'>Otwórz w nowej karcie</a>"
                            . "</p>"
                            . "</div>",
                        );
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Zamknij')
                    ->modalWidth('4xl'),

                // --- Zatwierdź ---
                Action::make('approve')
                    ->label('Zatwierdź')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Zatwierdź dokument')
                    ->modalDescription('Czy na pewno chcesz zatwierdzić ten dokument?')
                    ->modalSubmitActionLabel('Zatwierdź')
                    ->visible(fn (UserDocument $record): bool => $record->document_status !== DocumentStatus::APPROVED)
                    ->action(function (UserDocument $record): void {
                        $record->update([
                            'document_status'  => DocumentStatus::APPROVED,
                            'rejection_reason' => null,
                        ]);

                        Notification::make()
                            ->title('Dokument zatwierdzony')
                            ->success()
                            ->send();
                    }),

                // --- Odrzuć ---
                Action::make('reject')
                    ->label('Odrzuć')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->modalHeading('Odrzuć dokument')
                    ->modalDescription('Podaj przyczynę odrzucenia dokumentu.')
                    ->modalSubmitActionLabel('Odrzuć dokument')
                    ->visible(fn (UserDocument $record): bool => $record->document_status !== DocumentStatus::REJECTED)
                    ->form([
                        Textarea::make('rejection_reason')
                            ->label('Przyczyna odrzucenia')
                            ->placeholder('Np. Nieczytelny scan, brakuje strony 2, zdjęcie nie spełnia wymogów...')
                            ->required()
                            ->minLength(5)
                            ->maxLength(1000)
                            ->rows(4),
                    ])
                    ->action(function (UserDocument $record, array $data): void {
                        $record->update([
                            'document_status'  => DocumentStatus::REJECTED,
                            'rejection_reason' => $data['rejection_reason'],
                        ]);

                        Notification::make()
                            ->title('Dokument odrzucony')
                            ->body('Przyczyna została zapisana.')
                            ->danger()
                            ->send();
                    }),
            ]);
    }
}

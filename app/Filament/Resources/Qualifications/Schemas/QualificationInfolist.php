<?php

namespace App\Filament\Resources\Qualifications\Schemas;
use Filament\Infolists\Components\TextEntry;

use Filament\Schemas\Schema;

class QualificationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextEntry::make('name'),
                TextEntry::make('qualification_category_id'),
            ]);
    }
}

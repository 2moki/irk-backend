<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\StudyLevelFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name'])]
class StudyLevel extends Model
{
    /** @use HasFactory<StudyLevelFactory> */
    use HasFactory;

    /**
     * @return HasMany<Major, $this>
     */
    public function majors(): HasMany
    {
        return $this->hasMany(Major::class);
    }
}

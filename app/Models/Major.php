<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\MajorFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name',
    'semesters',
    'study_level_id',
    'study_mode_id',
    'degree_title_id',
])]
class Major extends Model
{
    /** @use HasFactory<MajorFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<StudyLevel, $this>
     */
    public function studyLevel(): BelongsTo
    {
        return $this->belongsTo(StudyLevel::class);
    }

    /**
     * @return BelongsTo<StudyMode, $this>
     */
    public function studyMode(): BelongsTo
    {
        return $this->belongsTo(StudyMode::class);
    }

    /**
     * @return BelongsTo<DegreeTitle, $this>
     */
    public function degreeTitle(): BelongsTo
    {
        return $this->belongsTo(DegreeTitle::class);
    }

    /**
     * @return BelongsToMany<Language, $this>
     */
    public function languages(): BelongsToMany
    {
        return $this->belongsToMany(Language::class);
    }

    /**
     * @return HasMany<Recruitment, $this>
     */
    public function recruitments(): HasMany
    {
        return $this->hasMany(Recruitment::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'semesters' => 'integer',
        ];
    }
}

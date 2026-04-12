<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Major extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'semesters',
        'study_level_id',
        'study_mode_id',
        'degree_title_id',
    ];

    public function studyLevel(): BelongsTo
    {
        return $this->belongsTo(StudyLevel::class);
    }

    public function studyMode(): BelongsTo
    {
        return $this->belongsTo(StudyMode::class);
    }

    public function degreeTitle(): BelongsTo
    {
        return $this->belongsTo(DegreeTitle::class);
    }
}

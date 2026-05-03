<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ExamType;
use Database\Factories\GradeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'min_value',
    'max_value',
    'conversion_rate',
    'multiplier',
    'is_bilingual',
    'grade_mapping_id',
    'exam_type',
])]
class Grade extends Model
{
    /** @use HasFactory<GradeFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<GradeMapping, $this>
     */
    public function gradeMapping(): BelongsTo
    {
        return $this->belongsTo(GradeMapping::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'min_value' => 'decimal:2',
            'max_value' => 'decimal:2',
            'conversion_rate' => 'decimal:2',
            'multiplier' => 'decimal:2',
            'is_bilingual' => 'boolean',
            'exam_type' => ExamType::class,
        ];
    }
}

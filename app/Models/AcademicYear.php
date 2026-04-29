<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BillingType;
use Database\Factories\AcademicYearFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'start_year',
    'billing_type',
    'grade_mapping_id',
])]
class AcademicYear extends Model
{
    /** @use HasFactory<AcademicYearFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<GradeMapping, $this>
     */
    public function gradeMapping(): BelongsTo
    {
        return $this->belongsTo(GradeMapping::class);
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
            'start_year' => 'integer',
            'billing_type' => BillingType::class,
        ];
    }
}

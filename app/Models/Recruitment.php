<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\RecruitmentStatus;
use App\Models\Pivots\RecruitmentApplication;
use Database\Factories\RecruitmentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read RecruitmentStatus $status
 */
#[Fillable([
    'start_date',
    'end_date',
    'slots',
    'major_id',
    'academic_year_id',
    'cost_id',
    'is_suspended',
])]
class Recruitment extends Model
{
    /** @use HasFactory<RecruitmentFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<Major, $this>
     */
    public function major(): BelongsTo
    {
        return $this->belongsTo(Major::class);
    }

    /**
     * @return BelongsTo<AcademicYear, $this>
     */
    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    /**
     * @return BelongsTo<Cost, $this>
     */
    public function cost(): BelongsTo
    {
        return $this->belongsTo(Cost::class);
    }

    /**
     * @return HasMany<RequirementGroup, $this>
     */
    public function requirementGroups(): HasMany
    {
        return $this->hasMany(RequirementGroup::class);
    }

    /**
     * @return BelongsToMany<Application, $this, RecruitmentApplication>
     */
    public function applications(): BelongsToMany
    {
        return $this->belongsToMany(Application::class, 'recruitment_application')
            ->using(RecruitmentApplication::class)
            ->withPivot(['got_points', 'max_points', 'priority', 'is_paid', 'payment_date', 'application_status'])
            ->withTimestamps();
    }

    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes): RecruitmentStatus => match (true) {
                $attributes['end_date'] < now() => RecruitmentStatus::FINISHED,
                $attributes['is_suspended'] => RecruitmentStatus::SUSPENDED,
                $attributes['start_date'] > now() => RecruitmentStatus::WAITING,
                default => RecruitmentStatus::ONGOING,
            },
        );
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_date' => 'immutable_date',
            'end_date' => 'immutable_date',
            'slots' => 'integer',
            'is_deactivated' => 'boolean',
        ];
    }
}

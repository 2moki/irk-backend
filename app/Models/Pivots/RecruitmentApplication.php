<?php

declare(strict_types=1);

namespace App\Models\Pivots;

use App\Enums\ApplicationStatus;
use App\Models\Application;
use App\Models\Language;
use App\Models\Recruitment;
use Database\Factories\Pivots\RecruitmentApplicationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

#[Table(name: 'recruitment_application', incrementing: true)]
#[Fillable([
    'application_id',
    'recruitment_id',
    'got_points',
    'max_points',
    'priority',
    'is_paid',
    'payment_date',
    'application_status',
])]
class RecruitmentApplication extends Pivot
{
    /** @use HasFactory<RecruitmentApplicationFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<Application, $this>
     */
    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    /**
     * @return BelongsTo<Recruitment, $this>
     */
    public function recruitment(): BelongsTo
    {
        return $this->belongsTo(Recruitment::class);
    }

    /**
     * @return BelongsToMany<Language, $this>
     */
    public function languages(): BelongsToMany
    {
        return $this->belongsToMany(Language::class, 'application_language')
            ->withPivot('priority')
            ->withTimestamps();
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'got_points' => 'decimal:2',
            'max_points' => 'decimal:2',
            'priority' => 'integer',
            'is_paid' => 'boolean',
            'payment_date' => 'immutable_date',
            'application_status' => ApplicationStatus::class,
        ];
    }
}

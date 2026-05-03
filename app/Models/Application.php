<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ExamType;
use Database\Factories\ApplicationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'user_id',
    'money_balance',
    'required_balance',
    'documents_delivered',
    'exam_type',
])]
class Application extends Model
{
    /** @use HasFactory<ApplicationFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsToMany<Recruitment, $this>
     */
    public function recruitments(): BelongsToMany
    {
        return $this->belongsToMany(Recruitment::class, 'recruitment_application')
            ->withPivot(['got_points', 'max_points', 'priority', 'is_paid', 'payment_date', 'application_status'])
            ->withTimestamps();
    }

    /**
     * @return HasMany<RecruitmentApplication, $this>
     */
    public function recruitmentApplications(): HasMany
    {
        return $this->hasMany(RecruitmentApplication::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'money_balance' => 'decimal:2',
            'required_balance' => 'decimal:2',
            'documents_delivered' => 'boolean',
            'exam_type' => ExamType::class,
        ];
    }
}

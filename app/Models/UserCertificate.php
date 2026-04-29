<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ExamType;
use Database\Factories\UserCertificateFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'user_id',
    'exam_type',
    'school_id',
    'school_custom_name',
    'issue_date',
    'is_annex',
    'document_number',
    'is_verified',
    'document_id',
])]
class UserCertificate extends Model
{
    /** @use HasFactory<UserCertificateFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<School, $this>
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * @return BelongsTo<UserDocument, $this>
     */
    public function document(): BelongsTo
    {
        return $this->belongsTo(UserDocument::class, 'document_id');
    }

    /**
     * @return HasMany<QualificationScore, $this>
     */
    public function qualificationScores(): HasMany
    {
        return $this->hasMany(QualificationScore::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'exam_type' => ExamType::class,
            'issue_date' => 'immutable_date',
            'is_annex' => 'boolean',
            'is_verified' => 'boolean',
        ];
    }
}

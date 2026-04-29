<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\DisabilityLevel;
use Database\Factories\CandidateDetailFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'nationality',
    'has_disability',
    'disability_level',
    'photo_document_id',
    'identity_document_id',
    'user_id',
])]
class CandidateDetail extends Model
{
    /** @use HasFactory<CandidateDetailFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<UserDocument, $this>
     */
    public function photoDocument(): BelongsTo
    {
        return $this->belongsTo(UserDocument::class, 'photo_document_id');
    }

    /**
     * @return BelongsTo<UserDocument, $this>
     */
    public function identityDocument(): BelongsTo
    {
        return $this->belongsTo(UserDocument::class, 'identity_document_id');
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'has_disability' => 'boolean',
            'disability_level' => DisabilityLevel::class,
        ];
    }
}

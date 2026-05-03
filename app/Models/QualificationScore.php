<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\QualificationScoreFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_certificate_id',
    'qualification_id',
    'value',
    'is_bilingual',
])]
class QualificationScore extends Model
{
    /** @use HasFactory<QualificationScoreFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<UserCertificate, $this>
     */
    public function userCertificate(): BelongsTo
    {
        return $this->belongsTo(UserCertificate::class);
    }

    /**
     * @return BelongsTo<Qualification, $this>
     */
    public function qualification(): BelongsTo
    {
        return $this->belongsTo(Qualification::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'value' => 'decimal:2',
            'is_bilingual' => 'boolean',
        ];
    }
}

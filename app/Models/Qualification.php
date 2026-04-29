<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\QualificationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'qualification_category_id'])]
class Qualification extends Model
{
    /** @use HasFactory<QualificationFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<QualificationCategory, $this>
     */
    public function qualificationCategory(): BelongsTo
    {
        return $this->belongsTo(QualificationCategory::class);
    }

    /**
     * @return HasMany<QualificationScore, $this>
     */
    public function scores(): HasMany
    {
        return $this->hasMany(QualificationScore::class);
    }
}

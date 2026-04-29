<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\SchoolType;
use Database\Factories\SchoolFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'rspo_id',
    'name',
    'city',
    'voivodeship',
    'school_type',
])]
class School extends Model
{
    /** @use HasFactory<SchoolFactory> */
    use HasFactory;

    /**
     * @return HasMany<UserCertificate, $this>
     */
    public function certificates(): HasMany
    {
        return $this->hasMany(UserCertificate::class);
    }

    /**
     * @return BelongsTo<Voivodeship, $this>
     */
    public function voivodeship(): BelongsTo
    {
        return $this->belongsTo(Voivodeship::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'school_type' => SchoolType::class,
        ];
    }
}

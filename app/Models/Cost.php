<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\CostFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['price'])]
class Cost extends Model
{
    /** @use HasFactory<CostFactory> */
    use HasFactory;

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
            'price' => 'decimal:2',
        ];
    }
}

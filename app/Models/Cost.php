<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['price'])]
class Cost extends Model
{
    public $timestamps = false;

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

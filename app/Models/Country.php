<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    public function voivodeships(): HasMany
    {
        return $this->hasMany(Voivodeship::class);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }
}

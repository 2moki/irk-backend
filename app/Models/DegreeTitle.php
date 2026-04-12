<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DegreeTitle extends Model
{
    protected $fillable = [
        'name',
    ];

    public function majors(): HasMany
    {
        return $this->hasMany(Major::class);
    }
}

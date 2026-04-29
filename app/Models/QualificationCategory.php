<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\QualificationCategoryFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name'])]
class QualificationCategory extends Model
{
    /** @use HasFactory<QualificationCategoryFactory> */
    use HasFactory;

    /**
     * @return HasMany<Qualification, $this>
     */
    public function qualifications(): HasMany
    {
        return $this->hasMany(Qualification::class);
    }
}

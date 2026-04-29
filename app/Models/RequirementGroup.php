<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\RequirementGroupFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable([
    'weight',
    'qualifications_count',
    'recruitment_id',
])]
class RequirementGroup extends Model
{
    /** @use HasFactory<RequirementGroupFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<Recruitment, $this>
     */
    public function recruitment(): BelongsTo
    {
        return $this->belongsTo(Recruitment::class);
    }

    /**
     * @return BelongsToMany<Qualification, $this>
     */
    public function qualifications(): BelongsToMany
    {
        return $this->belongsToMany(Qualification::class, 'group_major_qualification')
            ->withPivot('weight')
            ->withTimestamps();
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'weight' => 'decimal:2',
            'qualifications_count' => 'integer',
        ];
    }
}

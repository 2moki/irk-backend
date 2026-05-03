<?php

declare(strict_types=1);

namespace App\Models\Pivots;

use App\Models\Qualification;
use App\Models\RequirementGroup;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

#[Table(name: 'group_major_qualification', incrementing: true)]
#[Fillable(
    'requirement_group_id',
    'qualification_id',
    'weight',
)]
class RequirementGroupQualification extends Pivot
{
    public function requirementGroup(): BelongsTo
    {
        return $this->belongsTo(RequirementGroup::class);
    }

    public function qualification(): BelongsTo
    {
        return $this->belongsTo(Qualification::class);
    }
}

<?php

declare(strict_types=1);

namespace App\Sorts;

use App\Models\Major;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Sorts\Sort;

class MajorNameSort implements Sort
{
    public function __invoke(Builder $query, bool $descending, string $property): void
    {
        $direction = $descending ? 'desc' : 'asc';

        $query->orderBy(
            Major::select('name')->whereColumn('majors.id', 'recruitments.major_id'),
            $direction,
        );
    }
}

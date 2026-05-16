<?php

declare(strict_types=1);

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Spatie\QueryBuilder\Filters\Filter;

class RecruitmentStatusFilter implements Filter
{
    public function __invoke(Builder $query, mixed $value, string $property): void
    {
        $statuses = Arr::wrap($value);
        $now = now();

        $query->where(function (Builder $query) use ($statuses, $now): void {
            foreach ($statuses as $status) {
                $query->orWhere(function (Builder $q) use ($status, $now): void {
                    match ($status) {
                        'finished' => $q->where('end_date', '<', $now),
                        'suspended' => $q->where('end_date', '>=', $now)
                            ->where('is_suspended', true),
                        'waiting' => $q->where('end_date', '>=', $now)
                            ->where('is_suspended', false)
                            ->where('start_date', '>', $now),
                        'ongoing' => $q->where('end_date', '>=', $now)
                            ->where('is_suspended', false)
                            ->where('start_date', '<=', $now),
                        default => null,
                    };
                });
            }
        });
    }
}

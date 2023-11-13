<?php

declare(strict_types=1);

namespace MoisesK\LaravelLazyFilters;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

trait Limitable
{
    public function processLimit(Builder $query, ?Request $request = null): void
    {
        $request = $request ?? request();
        $limit = $request->query('limit');

        if (empty($limit)) {
            return;
        }

        $query->limit($limit);
    }
}

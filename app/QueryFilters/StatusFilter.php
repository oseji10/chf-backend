<?php

namespace App\QueryFilters;

use Closure;

class StatusFilter
{
    public function handle($query, Closure $next)
    {
        if (request('status')) {
            $query->where('status', request('status'));
        }

        return $next($query);
    }
}

<?php

namespace App\QueryFilters;

use Closure;

class RelationshipFilter
{
    public function handle($query, Closure $next)
    {
        if (request()->has('include')) {
            $query->with(request('include'));
        }

        return $next($query);
    }
}

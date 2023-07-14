<?php

namespace App\QueryFilters;

use App\Helpers\CHFConstants;

use Closure;

class SortFilter
{
    public function handle($query, Closure $next)
    {
        if (request()->has('sort')) {
            $sort_value = in_array(request('sort'), CHFConstants::$ALLOWED_SORT_VALUES) ? request('sort') : CHFConstants::$DEFAULT_SORT_KEY;
            $sort_key = request()->has('sort_by') ? request('sort_by') : CHFConstants::$DEFAULT_SORT_KEY;

            $query->orderBy($sort_key, $sort_value);
        }

        return $next($query);
    }
}

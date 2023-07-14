<?php

namespace App\QueryFilters;

use App\Helpers\CHFConstants;
use Closure;

class PaginationFilter
{
    public function handle($query, Closure $next)
    {
        $offset = request()->has('offset') ? request('offset') : CHFConstants::$DEFAULT_PAGINATION_OFFSET;
        $per_page = request()->has('per_page') ? request('per_page') : CHFConstants::$DEFAULT_DATA_PER_PAGE;

        $query->skip($offset)->take($per_page);

        return $next($query);
    }
}

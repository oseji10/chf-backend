<?php

namespace App\QueryFilters\User;

use Closure;

class UserFindFilter
{
    public function handle($query, Closure $next)
    {
        $id = request('id') ?? null;
        $query->where('id', $id)
            ->orWhere('email', $id)
            ->orWhere('phone_number', $id)
            ->orWhereHas('patient', function ($query) use ($id) {
                return $query->where('chf_id', $id);
            });

        return $next($query);
    }
}

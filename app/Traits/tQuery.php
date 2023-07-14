<?php

namespace App\Traits;

use App\QueryFilters\PaginationFilter;
use App\QueryFilters\RelationshipFilter;
use App\QueryFilters\SortFilter;
use App\QueryFilters\StatusFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pipeline\Pipeline;

trait tQuery
{
    public function getManyThroughPipe($model, array $otherPipes = [])
    {
        return app(Pipeline::class)
            ->send($model::query())
            ->through(array_merge([
                StatusFilter::class,
                RelationshipFilter::class,
                SortFilter::class,
                PaginationFilter::class,
            ], $otherPipes))
            ->thenReturn();
    }

    public function getOneThroughPipe($model, $id, array $otherPipes = [])

    {
        return app(Pipeline::class)
            ->send($model::query())
            ->through(array_merge([
                RelationshipFilter::class,
            ], $otherPipes))
            ->thenReturn()
            ->where('id', $id);
    }
}

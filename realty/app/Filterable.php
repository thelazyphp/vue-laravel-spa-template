<?php

namespace App;

use App\Filters\Filter;

trait Filterable
{
    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\Filters\Filter  $filter
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, Filter $filter)
    {
        return $filter->apply($query);
    }
}

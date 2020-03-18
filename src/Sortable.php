<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

trait Sortable
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function sort(Request $request, Builder $query)
    {
        $request->validate(['sort' => 'string']);

        if ($request->has('sort')) {
            $sortOrder = 'asc';
            $sortProp = str_replace('.', '_', $request->sort);

            if (strpos($sortProp, '-') === 0) {
                $sortOrder = 'desc';
                $sortProp = substr($sortProp, 1);
            }

            $query = $query->whereNotNull($sortProp)->orderBy($sortProp, $sortOrder);
        }

        return $query;
    }
}

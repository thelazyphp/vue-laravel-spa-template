<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function filter(Request $request, Builder $query)
    {
        $request->validate(['filter' => 'array']);

        foreach ($request->get('filter', []) as $filterProp => $filterValue) {
            $filterProp = str_replace('.', '_', $filterProp);

            if (strpos($filterProp, '_min') !== false) {
                $filterProp = str_replace('_min', '', $filterProp);
                $query = $query->where($filterProp, '>=', $filterValue);
            } else if (strpos($filterProp, '_max') !== false) {
                $filterProp = str_replace('_max', '', $filterProp);
                $query = $query->where($filterProp, '<=', $filterValue);
            } else {
                $query = $query->where($filterProp, $filterValue);
            }
        }

        return $query;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  array  $searchProps
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function search(Request $request, Builder $query, $searchProps)
    {
        $request->validate(['search' => 'string']);

        if ($request->has('search')) {
            $searchValue = $request->search;
            $keywords = preg_split('/[\s,;]+/', $searchValue);

            foreach ($keywords as $keyword) {
                $query = $query->where(function ($query) use ($searchProps, $keyword) {
                    foreach ($searchProps as $searchProp) {
                        $query = $query->orWhere($searchProp, 'like', "%{$keyword}%");
                    }
                });
            }
        }

        return $query;
    }
}

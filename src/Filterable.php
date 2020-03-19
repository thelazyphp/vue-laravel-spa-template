<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  array  $searchProps
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function search(Request $request, Builder $query, $searchProps = [])
    {
        $request->validate(['search' => 'string']);

        if ($request->has('search')) {
            $keywords = preg_split('/[\s,;]+/', $request->search);

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

            if (is_array($filterValue)) {
                $query = $query->whereIn($filterProp, $filterValue);
            } else if (strpos($filterProp, '_min') === false && strpos($filterProp, '_max') === false) {
                $query = $query->where($filterProp, $filterValue);
            } else if (strpos($filterProp, '_min') !== false) {
                $filterProp = str_replace('_min', '', $filterProp);
                $query = $query->whereNotNull($filterProp)->where($filterProp, '>=', $filterValue);
            } else if (strpos($filterProp, '_max') !== false) {
                $filterProp = str_replace('_max', '', $filterProp);
                $query = $query->whereNotNull($filterProp)->where($filterProp, '<=', $filterValue);
            }
        }

        return $query;
    }
}

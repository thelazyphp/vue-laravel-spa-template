<?php

namespace App\Filters;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

/**
 * @abstract
 */
abstract class Filter
{
    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $query;

    /**
     * @param  \Illuminate\Http\Request  $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Builder $query)
    {
        $this->query = $query;

        foreach ($this->request->query() as $key => $value) {
            if (method_exists($this, $key)) {
                call_user_func([$this, $key], $value);
            }
        }

        return $this->query;
    }
}

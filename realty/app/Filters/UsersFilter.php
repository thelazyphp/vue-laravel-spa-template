<?php

namespace App\Filters;

class UsersFilter extends Filter
{
    /**
     * @param  string  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function search($value)
    {
        return $this->query->where(
            'name',
            'like',
            '%'.$value.'%'
        );
    }

    /**
     * @param  string  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function sort($value)
    {
        $order = 'asc';

        if (strpos($value, '-') === 0) {
            $order = 'desc';
            $value = substr($value, 1);
        }

        return $this->query->orderBy($value, $order);
    }

    /**
     * @param  string  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function role($value)
    {
        return $this->query->where('role', $value);
    }
}

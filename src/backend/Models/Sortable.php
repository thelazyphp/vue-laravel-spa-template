<?php

namespace App\Models;

trait Sortable
{
    public function scopeSortBy($query, $prop)
    {
        $order = 'asc';

        if (strpos($prop, '-') === 0) {
            $order = 'desc';
            $prop = substr($prop, 1);
        }

        return $query->orderBy($prop, $order);
    }
}

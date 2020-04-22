<?php

namespace App\Models;

trait Filterable
{
    public function scopeFilter($query, $props)
    {
        foreach ($props as $key => $value) {
            if (is_array($value)) {
                if (
                    isset($value['min'])
                    || isset($value['max'])
                    || isset($value['not'])
                ) {
                    if (isset($value['min'])) {
                        $query = $query->where($key, '>=', $value['min']);
                    }

                    if (isset($value['max'])) {
                        $query = $query->where($key, '<=', $value['max']);
                    }

                    if (isset($value['not'])) {
                        $query = ($value['not'] === null)
                            ? $query->whereNotNull($key)
                            : $query->where($key, '<>', $value['not']);
                    }
                } else {
                    $query = $query->where(function ($query) use ($key, $value) {
                        foreach ($value as $v) {
                            if (! is_string($v)) {
                                $query = $query->orWhere($key, $v);
                            } else {
                                $len = strlen($v);

                                if (strpos($v, '-') === $len) {
                                    $v = substr($v, 0, $len - 1);
                                    $query = $query->orWhere($key, '<=', $v);
                                } else if (strpos($v, '+') === $len) {
                                    $v = substr($v, 0, $len - 1);
                                    $query = $query->orWhere($key, '>=', $v);
                                }
                            }
                        }
                    });
                }
            } else {
                if (! is_string($value)) {
                    $query = $query->where($key, $value);
                } else {
                    $len = strlen($value);

                    if (strpos($value, '-') === $len) {
                        $value = substr($value, 0, $len - 1);
                        $query = $query->where($key, '<=', $value);
                    } else if (strpos($value, '+') === $len) {
                        $value = substr($value, 0, $len - 1);
                        $query = $query->where($key, '>=', $value);
                    }
                }
            }
        }

        return $query;
    }
}

<?php

namespace App\Models;

trait Filterable
{
    public function scopeSearchBy($query, $value, $props)
    {
        $keywords = preg_split('/[\s,]+/', $value);

        foreach ($props as $prop) {
            $query = $query->orWhere(function ($query) use ($prop, $keywords) {
                foreach ($keywords as $keyword) {
                    $query = $query->orWhere($prop, 'like', "%{$keyword}%");
                }
            });
        }

        return $query;
    }

    public function scopeFilterBy($query, $props)
    {
        foreach ($props as $key => $value) {
            if (is_array($value)) {
                if (
                    isset($value['min'])
                    || isset($value['max'])
                    || isset($value['not'])
                    || isset($value['date'])
                    || isset($value['date_min'])
                    || isset($value['date_max'])
                    || isset($value['time'])
                    || isset($value['time_min'])
                    || isset($value['time_max'])
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

                    if (isset($value['date'])) {
                        $query = $query->whereDate($key, $value['date']);
                    }

                    if (isset($value['date_min'])) {
                        $query = $query->whereDate($key, '>=', $value['date_min']);
                    }

                    if (isset($value['date_max'])) {
                        $query = $query->whereDate($key, '<=', $value['date_max']);
                    }

                    if (isset($value['time'])) {
                        $query = $query->whereTime($key, $value['time']);
                    }

                    if (isset($value['time_min'])) {
                        $query = $query->whereTime($key, '>=', $value['time_min']);
                    }

                    if (isset($value['time_max'])) {
                        $query = $query->whereTime($key, '<=', $value['time_max']);
                    }
                } else {
                    $query = $query->where(function ($query) use ($key, $value) {
                        foreach ($value as $v) {
                            $len = strlen($v);

                            if (strpos($v, '-') === $len - 1) {
                                $v = substr($v, 0, $len - 1);
                                $query = $query->orWhere($key, '<=', (strpos($v, '.') === false) ? (int) $v : (float) $v);
                            } else if (strpos($v, '+') === $len - 1) {
                                $v = substr($v, 0, $len - 1);
                                $query = $query->orWhere($key, '>=', (strpos($v, '.') === false) ? (int) $v : (float) $v);
                            } else {
                                $query = ($v === null)
                                    ? $query->orWhereNull($key)
                                    : $query->orWhere($key, $v);
                            }
                        }
                    });
                }
            } else {
                $len = strlen($value);

                if (strpos($value, '-') === $len - 1) {
                    $value = substr($value, 0, $len - 1);
                    $query = $query->where($key, '<=', (strpos($value, '.') === false) ? (int) $value : (float) $value);
                } else if (strpos($value, '+') === $len - 1) {
                    $value = substr($value, 0, $len - 1);
                    $query = $query->where($key, '>=', (strpos($value, '.') === false) ? (int) $value : (float) $value);
                } else {
                    $query = ($value === null)
                        ? $query->whereNull($key)
                        : $query->where($key, $value);
                }
            }
        }

        return $query;
    }
}

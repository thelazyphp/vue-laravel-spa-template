<?php

namespace App\Filters;

class CatalogItemsFilter extends Filter
{
    /**
     * @param  string  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function search($value)
    {
        return $this->query->where(function ($query) use ($value) {
            return $query
                ->where(
                    'title',
                    'like',
                    '%'.$value.'%'
                )
                ->orWhere(
                    'address',
                    'like',
                    '%'.$value.'%'
                );
        });
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
    protected function transaction($value)
    {
        return $this->query->where('transaction', $value);
    }

    /**
     * @param  string  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function category($value)
    {
        return $this->query->where('category', $value);
    }

    /**
     * @param  string  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function type($value)
    {
        return $this->query->whereIn('type', explode(',', $value));
    }

    /**
     * @param  string  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function source($value)
    {
        return $this->query->whereIn('source', explode(',', $value));
    }

    /**
     * @param  string  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function rooms($value)
    {
        return $this->query->whereIn('rooms', explode(',', $value));
    }

    /**
     * @param  string  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function floor($value)
    {
        [$min, $max] = explode(',', $value);

        return $this->query->whereBetween('floor', [$min, $max]);
    }

    /**
     * @param  string  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function floors($value)
    {
        [$min, $max] = explode(',', $value);

        return $this->query->whereBetween('floors', [$min, $max]);
    }

    /**
     * @param  string  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function year_built($value)
    {
        [$min, $max] = explode(',', $value);

        return $this->query->whereBetween('year_built', [$min, $max]);
    }

    /**
     * @param  string  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function roof($value)
    {
        return $this->query->whereIn('rooms', explode(',', $value));
    }

    /**
     * @param  string  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function walls($value)
    {
        return $this->query->whereIn('rooms', explode(',', $value));
    }

    /**
     * @param  string  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function balcony($value)
    {
        return $this->query->whereIn('rooms', explode(',', $value));
    }

    /**
     * @param  string  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function bathroom($value)
    {
        return $this->query->whereIn('rooms', explode(',', $value));
    }

    /**
     * @param  string  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function size_land($value)
    {
        [$min, $max] = explode(',', $value);

        return $this->query->whereBetween('size_land', [$min, $max]);
    }

    /**
     * @param  string  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function size_total($value)
    {
        [$min, $max] = explode(',', $value);

        return $this->query->whereBetween('size_total', [$min, $max]);
    }

    /**
     * @param  string  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function size_living($value)
    {
        [$min, $max] = explode(',', $value);

        return $this->query->whereBetween('size_living', [$min, $max]);
    }

    /**
     * @param  string  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function size_kitchen($value)
    {
        [$min, $max] = explode(',', $value);

        return $this->query->whereBetween('size_kitchen', [$min, $max]);
    }

    /**
     * @param  string  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function price_amount($value)
    {
        [$min, $max] = explode(',', $value);

        return $this->query->whereBetween('price_amount', [$min, $max]);
    }

    /**
     * @param  string  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function price_sq_m_amount($value)
    {
        [$min, $max] = explode(',', $value);

        return $this->query->whereBetween('price_sq_m_amount', [$min, $max]);
    }
}

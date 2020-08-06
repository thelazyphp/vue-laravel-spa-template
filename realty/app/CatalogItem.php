<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CatalogItem extends Model
{
    const TRANSACTION_SELL = 'sell';
    const TRANSACTION_RENT = 'rent';

    const CATEGORY_APARTMENTS = 'apartments';
    const CATEGORY_LANDS = 'lands';
    const CATEGORY_HOUSES = 'houses';
    const CATEGORY_COMMERCIAL_REAL_ESTATE = 'commercial_real_estate';

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'transaction' => self::TRANSACTION_SELL,
        'images' => '[]',
        'price_currency' => 'USD',
        'price_sq_m_currency' => 'USD',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'seller_id',
        'transaction',
        'category',
        'type',
        'source',
        'url',
        'images',
        'title',
        'address',
        'rooms',
        'floor',
        'floors',
        'year_built',
        'size_land',
        'size_total',
        'size_living',
        'size_kitchen',
        'roof',
        'walls',
        'balcony',
        'bathroom',
        'price_amount',
        'price_currency',
        'price_sq_m_amount',
        'price_sq_m_currency',
        'published_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'images' => 'array',
        'rooms' => 'integer',
        'floor' => 'integer',
        'floors' => 'integer',
        'year_built' => 'integer',
        'size_land' => 'float',
        'size_total' => 'float',
        'size_living' => 'float',
        'size_kitchen' => 'float',
        'price_amount' => 'integer',
        'price_sq_m_amount' => 'integer',
        'published_at' => 'datetime',
    ];

    /**
     * @return bool
     */
    public function isFavorite()
    {
        return Favorite::where('user_id', auth()->id())
            ->where('catalog_item_id', $this->id)
            ->exists();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function seller()
    {
        return $this->belongsTo('App\Seller');
    }
}

<?php

namespace App\Models\Catalog;

use App\Models\Sortable;
use App\Models\Filterable;
use App\Models\Favoritable;
use Illuminate\Database\Eloquent\Model;

class Apartment extends Model
{
    const SELL_TYPE_RENT = 'rent';
    const SELL_TYPE_SELL = 'sell';

    use Sortable, Filterable, Favoritable;

    const FILTER_OPTIONS = [
        'sell_type' => [
            self::SELL_TYPE_RENT => 'Аренда',
            self::SELL_TYPE_SELL => 'Продажа',
        ],

        'walls' => [
            'п' => 'Панельные',
            'м' => 'Монолитные',
            'к' => 'Кирпичные',
            'бл' => 'Блочные',
            'карк' => 'Каркасные',
            'бр' => 'Бревенчатые',
        ],

        'balcony' => [
            'нет' => 'Нет',
            'б' => 'Балкон',
            'л' => 'Лоджия',
            'б+л' => 'Балкон и лоджия',
            '2б' => 'Два балкона',
            '2л' => 'Две лоджии',
            '3б+' => 'Три и более балкона',
            '3л+' => 'Три и более лоджии',
        ],

        'bathroom' => [
            'р' => 'Раздельный',
            'с' => 'Совмещенный',
            'два' => 'Два санузла',
            '3+' => 'Три и более санузла',
        ],
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'catalog_apartments';

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'sell_type' => self::SELL_TYPE_SELL,
        'seller_is_private' => false,
        'price_currency' => 'USD',
        'is_published' => true,
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
        'size_total' => 'float',
        'size_living' => 'float',
        'size_kitchen' => 'float',
        'seller_is_private' => 'boolean',
        'seller_company_phones' => 'array',
        'seller_contact_person_phones' => 'array',
        'price_history' => 'array',
        'price_per_sqm' => 'float',
        'price_amount' => 'integer',
        'address_coordinates' => 'array',
        'is_published' => 'boolean',
    ];
}

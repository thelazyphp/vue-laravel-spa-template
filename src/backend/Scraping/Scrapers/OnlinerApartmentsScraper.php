<?php

namespace App\Scraping\Scrapers;

use App\Scraping\Scraper;
use App\Scraping\Facades\Rule;

class OnlinerApartmentsScraper extends Scraper
{
    /**
     * {@inheritDoc}
     */
    protected $model = 'App\CatalogApartment';

    /**
     * {@inheritDoc}
     */
    protected function registerRules()
    {
        $this->rules = [
            'source_id' => Rule::relationship('App\Source', [
                'name'   => 'Онлайнер',
                'url'    => 'https://www.onliner.by/',
                'logo'   => 'https://gc.onliner.by/images/logo/icons/favicon-192x192.png',
                'agency' => false,
            ]),

            'seller_id' => Rule::relationship('App\Seller', [
                'type' => function ($url, $src, $attr) {
                    return Rule::findWithText('.apartment-bar__value', 'Собственник')->castToBoolean()->scrap($src, false) ? \App\Seller::TYPE_OWNER : \App\Seller::TYPE_AGENT;
                },

                'name' => Rule::find('a[href^=https://profile.onliner.by/user/]'),

                'phones' => Rule::find('.apartment-info__list_phones a[href^=tel:]')->each(function () {
                    return Rule::takeDigits()->prepend('+');
                }),
            ]),

            'location_id' => Rule::relationship('App\Location', [
                'street' => Rule::find('.apartment-info__sub-line_large'),
            ]),

            'transaction' => function ($url, $src, $attr) {
                return strpos($url, '/ak/') !== false ? \App\CatalogApartment::TRANSACTION_RENT : \App\CatalogApartment::TRANSACTION_SELL;
            },

            'images' => Rule::findAll('.apartment-gallery__slide')->each(function ($html) {
                return [
                    'src' => Rule::attr('style')->match('/url\((.+)\)/')->scrap($html),
                ];
            }),

            'title' => Rule::find('title'),
            'rooms' => Rule::find('.apartment-bar__value')->takeInteger(),
            'floor' => Rule::findWithText('.apartment-options-table__sub', 'Этаж')->parent(3)->lastChild()->takeInteger(),
            'floors' => Rule::findWithText('.apartment-options-table__sub', 'Этаж')->parent(3)->lastChild()->match('/\d+\/(\d+)/'),
            'year_built' => Rule::findWithTextMatches('.apartment-options__item', '/ дом \d+ года/')->takeInteger(),
            'size_total' => Rule::findWithText('.apartment-options-table__sub', 'Общая')->parent(3)->lastChild()->takeFloat()->castToFloat(),
            'size_living' => Rule::findWithText('.apartment-options-table__sub', 'Жилая')->parent(3)->lastChild()->takeFloat()->castToFloat(),
            'size_kitchen' => Rule::findWithText('.apartment-options-table__sub', 'Кухня')->parent(3)->lastChild()->takeFloat()->castToFloat(),
            'price_amount' => Rule::find('.apartment-bar__price-value_complementary')->removeSpaces()->takeInteger(),
            'price_currency' => 'USD',

            'price_sq_m_amount' => function ($url, $src, $attr) {
                if ($attr['price_amount'] && $attr['size_total']) {
                    return round($attr['price_amount'] / $attr['size_total']);
                }
            },

            'price_sq_m_currency' => function ($url, $src, $attr) {
                if ($attr['price_amount'] && $attr['size_total']) {
                    return $attr['price_currency'];
                }
            },
        ];
    }
}

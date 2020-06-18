<?php

namespace App\Scrapers;

use App\Scraping\EloquentScraper;
use App\Scraping\Facades\Rule;
use App\CatalogApartment;
use App\Seller;
use GuzzleHttp\Psr7\Uri;
use simple_html_dom;

class DomovitaApartments extends EloquentScraper
{
    protected const REGIONS = [
        'https://domovita.by/brest/flats/sale',
    ];

    protected const PER_PAGE = 20;

    protected $model = CatalogApartment::class;

    public function __construct()
    {
        parent::__construct();

        $this->fill = [
            'source_id' => Rule::relationship('App\Source')
                ->uniqueKey('url')
                ->rules([
                    'agency' => false,
                    'name'   => 'Domovita',
                    'url'    => 'https://domovita.by/',
                ]),
        ];
    }

    protected function crawleLinks()
    {
        $links = [];

        foreach (self::REGIONS as $region) {
            $html = $this->getHtml($region);
            $total = Rule::find('.findcount')->takeInteger()->scrape($html);
            $lastPage = ceil($total / self::PER_PAGE);

            for ($page = 1; $page <= $lastPage; $page++) {
                $html = $this->getHtml($region.'?page='.$page);

                foreach (Rule::findAll('.link-object')->each(function () { return Rule::attr('href'); })->scrape($html) as $link) {
                    $links[] = $link;
                }
            }
        }

        return $links;
    }

    protected function registerRules()
    {
        $this->rules['seller_id'] = Rule::relationship('App\Seller')
            ->uniqueKey(['email', 'phones'])
            ->rules([
                'type' => function (
                    Uri $url,
                    simple_html_dom $html,
                    $attr)
                {
                    return Rule::find('.owner-info__status')->scrape($html) == 'Собственник'
                        ? Seller::TYPE_OWNER
                        : Seller::TYPE_AGENT;
                },

                'email' => Rule::find('.owner-info__email'),

                'phones' => Rule::findAll('.owner-info__phone [href^=tel:]')->each(function () {
                    return Rule::attr('href')->takeDigits()->prepend('+');
                }),
            ]);

        $this->rules['location_id'] = Rule::relationship('App\Location')
            ->rules([
                'locality' => Rule::findWithText('.object-info__parametr span', 'Город')->nextSibling(),
                'district' => Rule::findWithText('.object-info__parametr span', 'Микрорайон')->nextSibling(),
                'street'   => Rule::findWithText('.object-info__parametr span', 'Адрес')->nextSibling(),
            ]);

        $this->rules['title'] = Rule::find('.object-head__name h1');

        $this->rules['images'] = Rule::findAll('.gallery img')->each(function ($element) {
            $src = Rule::attr('data-src')->scrape($element);

            return [
                'src'   => $src,
                'thumb' => $src,
            ];
        });

        $this->rules['rooms'] = Rule::findWithText('.object-info__parametr span', 'Комнат')->nextSibling()->takeInteger();

        $this->rules['floor'] = Rule::findWithText('.object-info__parametr span', 'Этаж')->nextSibling()->takeInteger();

        $this->rules['floors'] = Rule::findWithText('.object-info__parametr span', 'Этажность')->nextSibling()->takeInteger();

        $this->rules['year_built'] = Rule::findWithText('.object-info__parametr span', 'Год постройки')->nextSibling()->takeInteger();

        $this->rules['walls'] = Rule::findWithText('.object-info__parametr span', 'Материал стен')->nextSibling();

        $this->rules['balcony'] = Rule::findWithText('.object-info__parametr span', 'Балкон')->nextSibling();

        $this->rules['bathroom'] = Rule::findWithText('.object-info__parametr span', 'Санузел')->nextSibling();

        $this->rules['size_total'] = Rule::findWithText('.object-info__parametr span', 'Общая площадь')->nextSibling()->takeNumeric();

        $this->rules['size_living'] = Rule::findWithText('.object-info__parametr span', 'Жилая площадь')->nextSibling()->takeNumeric();

        $this->rules['size_kitchen'] = Rule::findWithText('.object-info__parametr span', 'Кухня')->nextSibling()->takeNumeric();

        $this->rules['price_currency'] = 'USD';

        $this->rules['price_amount'] = Rule::find('.calculator__price-main')->takeDigits();

        $this->rules['price_sq_m_currency'] = 'USD';

        $this->rules['price_sq_m_amount'] = Rule::find('.calculator__price-per-meter')->takeDigits()->replaceByPattern('/2$/', '');

        $this->rules['published_at'] = Rule::find('.publication-info__update-date')->replace('Обновлено: ', '')->castToDateTime('Y-m-d H:i:s');
    }

    protected function after(
        Uri $url,
        simple_html_dom $html,
        &$attrs)
    {
        $attrs['transaction'] = strpos($url->getPath(), 'rent') !== false
            ? CatalogApartment::TRANSACTION_RENT
            : CatalogApartment::TRANSACTION_SELL;
    }
}

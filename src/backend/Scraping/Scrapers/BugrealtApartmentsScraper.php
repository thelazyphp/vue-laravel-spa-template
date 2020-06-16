<?php

namespace App\Scraping\Scrapers;

use App\Scraping\EloquentScraper;
use App\Scraping\Facades\Rule;
use GuzzleHttp\Psr7\Uri;

class BugrealtApartmentsScraper extends EloquentScraper
{
    protected $model = 'App\CatalogApartment';

    protected function scrapeLinks()
    {
        $links = collect();

        $regions = [
            'https://bugrealt.by/flat-sale-89-s/',
            // 'https://bugrealt.by/newflat-18-s/',
            // 'https://pinsk.bugrealt.by/flat-sale-89-s/',
            // 'https://minsk.bugrealt.by/flat-sale-89-s/',
            // 'https://minsk.bugrealt.by/newflat-18-s/',
            // 'https://grodno.bugrealt.by/flat-sale-89-s/',
            // 'https://grodno.bugrealt.by/newflat-18-s/',
        ];

        foreach ($regions as $region) {
            $baseUrl = (new Uri($region))->withPath('');
            $html = $this->getHtml($region);
            $perPage = Rule::find('.catalog-product__btn-more')->takeInteger()->scrape($html, 1);
            $total = Rule::find('.catalog-filter__result-count')->takeInteger()->scrape($html, 1);
            // $lastPage = ceil($total / $perPage);
            $lastPage = 1;

            for ($page = 1; $page <= $lastPage; $page++) {
                $html = $this->getHtml($region.'?cpage=page-'.$page);

                $scraped = Rule::findAll('.catalog-product__card__container')->each(function () use ($baseUrl) {
                    return Rule::attr('href')->prepend($baseUrl);
                })->scrape($html);

                foreach ($scraped as $link) {
                    $links->push($link);
                }
            }
        }

        return $links;
    }

    protected function registerRules()
    {
        $this->rules['images'] = Rule::findAll('.product-slider-nav-item')->each(function ($element, $index) {
            return [
                'src' => Rule::attr('href')->prepend('https://bugrealt.by')->scrape($element),
                'thumb' => Rule::find('.product-slider-nav-img')->attr('src')->prepend('https://bugrealt.by')->scrape($element),
            ];
        });

        $this->rules['title'] = Rule::find('.product-paper-title');

        $this->rules['rooms'] = Rule::find('[itemprop=position][content=4]')->prevSibling()->takeInteger();

        $this->rules['floor'] = Rule::findWithText('.extra_fields .title', 'Этаж квартиры:')->nextSibling()->takeInteger();

        $this->rules['floors'] = Rule::findWithText('.extra_fields .title', 'Этажность:')->nextSibling()->takeInteger();

        $this->rules['year_built'] = Rule::findWithText('.extra_fields .title', 'Год постройки:')->nextSibling()->takeInteger();

        $this->rules['walls'] = Rule::findWithText('.extra_fields .title', 'Материал стен:')->nextSibling();

        $this->rules['balcony'] = Rule::findWithText('.extra_fields .title', 'Балкон:')->nextSibling();

        $this->rules['bathroom'] = Rule::findWithText('.extra_fields .title', 'Санузел:')->nextSibling();

        $this->rules['size_total'] = Rule::findWithText('.extra_fields .title', 'общая:')->nextSibling()->takeNumeric();

        $this->rules['size_living'] = Rule::findWithText('.extra_fields .title', 'жилая:')->nextSibling()->takeNumeric();

        $this->rules['size_kitchen'] = Rule::findWithText('.extra_fields .title', 'кухня:')->nextSibling()->takeNumeric();

        $this->rules['price_currency'] = 'USD';

        $this->rules['price_amount'] = Rule::find('.product-price-format', 1)->takeDigits();

        $this->rules['price_sq_m_currency'] = 'USD';

        $this->rules['price_sq_m_amount'] = Rule::find('.product-price-format', 3)->takeDigits();
    }
}

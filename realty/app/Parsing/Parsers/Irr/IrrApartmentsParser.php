<?php

namespace App\Parsing\Parsers\Irr;

use App\CatalogItem;
use App\Parsing\DOM\Document;
use App\Parsing\Facades\Rule;
use App\Parsing\Parsers\Parser;

class IrrApartmentsParser extends Parser
{
    /**
     * {@inheritDoc}
     */
    protected $category = CatalogItem::CATEGORY_APARTMENTS;

    /**
     * {@inheritDoc}
     */
    protected $source = 'irr.by';

    /**
     * {@inheritDoc}
     */
    protected function registerRules()
    {
        $this->rules['type'] = Rule::find('.cf_block_housetype')->lastChild()->text();

        $this->rules['images'] = Rule::findAll('.nyroModal')->map(function ($element) {
            return [
                'src' => Rule::attr('href')->evaluate($element),
                'thumb' => Rule::find('img')->attr('src')->evaluate($element),
            ];
        });

        $this->rules['title'] = Rule::find('.title3')->text();

        $this->rules['address'] = Rule::find('.address_link')->text();

        $this->rules['rooms'] = Rule::find('.cf_block_rooms')->lastChild()->takeInteger();

        $this->rules['floor'] = Rule::find('.cf_block_etage')->lastChild()->takeInteger();

        $this->rules['floors'] = Rule::find('.cf_block_etage_all')->lastChild()->takeInteger();

        $this->rules['year_built'] = Rule::find('.cf_block_house_year')->lastChild()->takeInteger();

        $this->rules['size_total'] = Rule::find('.cf_block_meters_total')->lastChild()->takeNumeric();

        $this->rules['size_living'] = Rule::find('.cf_block_meters_living')->lastChild()->takeNumeric();

        $this->rules['size_kitchen'] = Rule::find('.cf_block_kitchen')->lastChild()->takeNumeric();

        $this->rules['walls'] = Rule::find('.cf_block_walltype')->lastChild()->text();

        $this->rules['balcony'] = Rule::find('.cf_block_balcony')->lastChild()->text();

        $this->rules['bathroom'] = Rule::find('.cf_block_toilet')->lastChild()->text();

        $this->rules['price_amount'] = Rule::findWhereTextContains('.credit_cost li', '$')->takeDigits();

        $publishedAt = [
            'января'   => 'jan',
            'февраля'  => 'feb',
            'марта'    => 'mar',
            'апреля'   => 'apr',
            'мая'      => 'may',
            'июня'     => 'jun',
            'июля'     => 'jul',
            'августа'  => 'aug',
            'сентября' => 'sep',
            'октября'  => 'oct',
            'ноября'   => 'nov',
            'декабря'  => 'dec',
        ];

        $this->rules['published_at'] = Rule::find('.data')->replace(array_keys($publishedAt), array_values($publishedAt))->toDateTime();
    }

    /**
     * {@inheritDoc}
     */
    protected function parseLinks(?int $limit = null): array
    {
        $endpoints = [
            'http://belarus.irr.by/realestate/sale-flats/',
        ];

        foreach ($endpoints as $endpoint) {
            $html = (string) $this->client->get($endpoint)->getBody();

            $document = new Document();
            $document->loadHTML($html);

            $lastPage = Rule::findLast('.js-paginatorPage')->takeInteger()->evaluate($document);
            $links = [];

            for ($page = 1; $page <= 1; $page++) {
                $html = (string) $this->client->get("{$endpoint}search/list=list/page{$page}/")->getBody();
                $document->loadHTML($html);

                foreach ($document->querySelectorAll('.add_title') as $element) {
                    $links[] = $element->getAttribute('href');
                }
            }

            return $links;
        }
    }
}

<?php

namespace App\Parsing\Parsers;

use App\Parsing\Parser;
use App\Parsing\Rule;

class OnlinerCatalogApartmentsParser extends Parser
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
        $this->rules['seller_contact_person'] = (new Rule)->findFirst('[href^=https://profile.onliner.by/user/]');

        $this->rules['seller_phone'] = (new Rule)
            ->findFirst('[href^=tel]')
            ->takeDigits();

        $this->rules['seller_company_name'] = (new Rule)->findFirstWhereTextHas('.apartment-info__sub-line_complementary', 'УНП: ')
            ->findFirst('.apartment-info__sub-title');

        $this->rules['title'] = (new Rule)->findFirst('title');
        $this->rules['description'] = (new Rule)->findFirst('.apartment-info__sub-line_extended-bottom');

        $this->rules['rooms'] = (new Rule)->findFirst('.apartment-bar__value')
            ->takeNumeric()
            ->castToInteger();

        $this->rules['floor'] = (new Rule)->findFirstWhereText('.apartment-options-table__sub', 'Этаж')
            ->parent()
            ->parent()
            ->parent()
            ->findFirst('.apartment-options-table__cell_right')
            ->match('/(\d+)(?:\/\d+)?/', 1)
            ->castToInteger();

        $this->rules['floors'] = (new Rule)->findFirstWhereText('.apartment-options-table__sub', 'Этаж')
            ->parent()
            ->parent()
            ->parent()
            ->findFirst('.apartment-options-table__cell_right')
            ->match('/\d+\/(\d+)/', 1)
            ->castToInteger();

        $this->rules['year_built'] = (new Rule)->findFirstWhereTextHas('.apartment-options__item', ' года')
            ->takeNumeric()
            ->castToInteger();

        $this->rules['size_total'] = (new Rule)->findFirstWhereText('.apartment-options-table__sub', 'Общая')
            ->parent()
            ->parent()
            ->parent()
            ->findFirst('.apartment-options-table__cell_right')
            ->takeNumeric()
            ->castToFloat();

        $this->rules['size_living'] = (new Rule)->findFirstWhereText('.apartment-options-table__sub', 'Жилая')
            ->parent()
            ->parent()
            ->parent()
            ->findFirst('.apartment-options-table__cell_right')
            ->takeNumeric()
            ->castToFloat();

        $this->rules['size_kitchen'] = (new Rule)->findFirstWhereText('.apartment-options-table__sub', 'Кухня')
            ->parent()
            ->parent()
            ->parent()
            ->findFirst('.apartment-options-table__cell_right')
            ->takeNumeric()
            ->castToFloat();

        $this->rules['price_total_amount'] = (new Rule)->findFirst('.apartment-bar__price-value_complementary')
            ->takeDigits()
            ->castToInteger();

        $this->rules['price_total_currency'] = (new Rule)->defineAsConstValue('USD');
        $this->rules['location_address_formatted'] = (new Rule)->findFirst('.apartment-info__sub-line_large');
    }

    /**
     * {@inheritDoc}
     */
    protected function parseLinks()
    {
        return ['https://r.onliner.by/pk/apartments/352492'];
    }
}

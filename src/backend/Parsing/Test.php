<?php

namespace App\Crawling\Crawlers;

use App\Crawling\Crawler;
use function file_get_html;

class Test
{
    /**
     * @return void
     */
    public function run()
    {
        $attributes = [];

        $html = file_get_html(
            'https://a-brest.by/catalog/kvartiry/160019/'
        );

        $attributes['seller_contact_person'] = (new Crawler($html))->findLast('.sf-name')->lastChild()->text()->get();
        $attributes['title'] = (new Crawler($html))->find('.d-h1-wrap h1')->text()->trim()->get();
        $attributes['description'] = (new Crawler($html))->find('.dt-content-h p')->text()->trim()->get();
        $attributes['rooms'] = (new Crawler($html))->findWhereText('.line-prop dt', 'Вид объекта')->nextSibling()->text()->takeInteger()->get();
        $attributes['floor'] = (new Crawler($html))->findWhereText('.line-prop dt', 'Этаж квартиры')->nextSibling()->text()->takeInteger()->get();
        $attributes['floors'] = (new Crawler($html))->findWhereText('.line-prop dt', 'Этажность дома')->nextSibling()->text()->takeInteger()->get();
        $attributes['year_built'] = (new Crawler($html))->findWhereText('.line-prop dt', 'Год постройки')->nextSibling()->text()->takeInteger()->get();
        $attributes['walls'] = (new Crawler($html))->findWhereText('.line-prop dt', 'Материал стен')->nextSibling()->text()->trim()->get();
        $attributes['balcony'] = (new Crawler($html))->findWhereTextMatches('.line-prop dt', '/(?:Балкон)|(?:Лоджия)/')->nextSibling()->text()->trim()->get();
        $attributes['bathroom'] = (new Crawler($html))->findWhereText('.line-prop dt', 'Санузел')->nextSibling()->text()->trim()->get();
        $attributes['size_total'] = (new Crawler($html))->findWhereText('.line-prop dt', 'Общая площадь')->nextSibling()->text()->takeFloat()->get();
        $attributes['size_living'] = (new Crawler($html))->findWhereText('.line-prop dt', 'Жилая площадь')->nextSibling()->text()->takeFloat()->get();
        $attributes['size_kitchen'] = (new Crawler($html))->findWhereText('.line-prop dt', 'Площадь кухни')->nextSibling()->text()->takeFloat()->get();
        $attributes['price_total_amount'] = (new Crawler($html))->find('#csTrue')->attribute('data-cost')->get();
        $attributes['price_total_currency'] = 'USD';

       if ($attributes['price_total_amount'] && $attributes['size_total']) {
            $attributes['price_sq_m_amount'] = round($attributes['price_total_amount'] / $attributes['size_total']);
            $attributes['price_sq_m_currency'] = 'USD';
       }

        echo '<pre>';
        print_r($attributes); die;
    }
}

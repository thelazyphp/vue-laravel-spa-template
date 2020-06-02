<?php

namespace App\Crawling;

use function file_get_html;
use App\Crawling\Crawler;

class TestCrawler
{
    /**
     * @return void
     */
    public function __invoke()
    {
        //
    }

    /**
     * @param string $url
     * @return void
     */
    protected function crawleFromRealtBy($url)
    {
        $html = file_get_html($url);

        $attr = [];

        $attr['seller_contact_person_name'] = Crawler::source($html)->find('.contacts .name a')->getResult();

        $attr['seller_contact_person_email'] = Crawler::source($html)->findWhereText('.table-row-left', 'E-mail')->nextSibling()->getResult();

        $attr['seller_contact_person_phones'] = Crawler::source($html)->findAll('.phone-operator')->each(function (Crawler $crawler) {
            return $crawler->takeDigits()->getResult();
        })->getResult()->all();

        $attr['seller_company'] = Crawler::source($html)->findWhereText('.table-row-left', 'Агентство')->nextSibling()->getResult();

        $attr['images'] = Crawler::source($html)->findAll('.lightgallery')->each(function (Crawler $crawler) {
            return $crawler->attribute('href')->getResult();
        })->getResult()->all();

        $attr['title'] = Crawler::source($html)->find('.f24')->removeHtmlEntities()->getResult();

        $attr['description'] = Crawler::source($html)->find('.description')->replaceMatched(['/[\r\n]/', '/\s{2,}/'], ' ')->removeHtmlEntities()->getResult();

        $attr['rooms'] = Crawler::source($html)->findWhereText('.table-row-left', 'Комнат всего/разд.')->nextSibling()->match('/^\d+/')->castToInteger()->getResult();

        $attr['floor'] = Crawler::source($html)->findWhereText('.table-row-left', 'Этаж / этажность')->nextSibling()->match('/^\d+/')->castToInteger()->getResult();

        $attr['floors'] = Crawler::source($html)->findWhereText('.table-row-left', 'Этаж / этажность')->nextSibling()->match('/\d+$/')->castToInteger()->getResult();

        $attr['year_built'] = Crawler::source($html)->findWhereText('.table-row-left', 'Год постройки')->nextSibling()->takeInteger()->castToInteger()->getResult();

        $attr['size_total'] = Crawler::source($html)->findWhereText('.table-row-left', 'Площадь общая/жилая/кухня')->nextSibling()->explode('/')->each(function (Crawler $crawler) {
            return $crawler->takeFloat()->castToFloat()->getResult();
        })->getResult()->get(0);

        $attr['size_living'] = Crawler::source($html)->findWhereText('.table-row-left', 'Площадь общая/жилая/кухня')->nextSibling()->explode('/')->each(function (Crawler $crawler) {
            return $crawler->takeFloat()->castToFloat()->getResult();
        })->getResult()->get(1);

        $attr['size_kitchen'] = Crawler::source($html)->findWhereText('.table-row-left', 'Площадь общая/жилая/кухня')->nextSibling()->explode('/')->each(function (Crawler $crawler) {
            return $crawler->takeFloat()->castToFloat()->getResult();
        })->getResult()->get(2);

        $attr['price_total_amount'] = Crawler::source($html)->find('.price-switchable')->attribute('data-0')->match('/(.+)\$,/', 1)->removeHtmlEntities()->castToInteger()->getResult();

        $attr['location_address_components_province'] = Crawler::source($html)->findWhereText('.table-row-left', 'Область')->nextSibling()->getResult();

        $attr['location_address_components_area'] = Crawler::source($html)->findWhereText('.table-row-left', 'Район (области)')->nextSibling()->getResult();

        $attr['location_address_components_locality'] = Crawler::source($html)->findWhereText('.table-row-left', 'Населенный пункт')->nextSibling()->getResult();

        $attr['location_address_components_street'] = Crawler::source($html)->findWhereText('.table-row-left', 'Адрес')->nextSibling()->getResult();

        dd($attr);
    }

    /**
     * @param string $url
     * @return void
     */
    protected function crawleFromOnlinerBy($url)
    {
        $html = file_get_html($url);

        $attr = [];

        $attr['seller_contact_person_name'] = Crawler::source($html)->find('.apartment-info__sub-line_extended a[href^=https://profile.onliner.by/user/]')->getResult();

        $attr['seller_contact_person_phones'] = Crawler::source($html)->findAll('.apartment-info__item_secondary a[href^=tel:]')->each(function (Crawler $crawler) {
            return $crawler->takeDigits()->getResult();
        })->getResult()->all();

        $attr['seller_company'] = Crawler::source($html)->findWhereTextContains('.apartment-info__sub-line_complementary', 'УНП:')->find('.apartment-info__sub-title')->removeHtmlEntities()->getResult();

        $attr['images'] = Crawler::source($html)->findAll('.apartment-gallery__slide')->each(function (Crawler $crawler) {
            return $crawler->attribute('style')->match('/url\((.+)\)/', 1)->getResult();
        })->getResult()->all();

        $attr['title'] = Crawler::source($html)->find('title')->removeHtmlEntities()->getResult();

        $attr['description'] = Crawler::source($html)->find('.apartment-info__sub-line_extended-bottom')->replaceMatched(['/[\r\n]/', '/\s{2,}/'], ' ')->removeHtmlEntities()->getResult();

        $attr['rooms'] = Crawler::source($html)->find('.apartment-bar__value')->takeInteger()->castToInteger()->getResult();

        $attr['floor'] = Crawler::source($html)->findWhereText('.apartment-options-table__sub', 'Этаж')->parent()->parent()->parent()->lastChild()->match('/(\d+)(?:\/\d+)?/', 1)->castToInteger()->getResult();

        $attr['floors'] = Crawler::source($html)->findWhereText('.apartment-options-table__sub', 'Этаж')->parent()->parent()->parent()->lastChild()->match('/\d+\/(\d+)/', 1)->castToInteger()->getResult();

        $attr['year_built'] = Crawler::source($html)->findWhereTextMatches('.apartment-options__item', '/дом\s+\d+\s+года/')->takeInteger()->castToInteger()->getResult();

        $attr['size_total'] = Crawler::source($html)->findWhereText('.apartment-options-table__sub', 'Общая')->parent()->parent()->parent()->lastChild()->takeFloat()->castToFloat()->getResult();

        $attr['size_living'] = Crawler::source($html)->findWhereText('.apartment-options-table__sub', 'Жилая')->parent()->parent()->parent()->lastChild()->takeFloat()->castToFloat()->getResult();

        $attr['size_kitchen'] = Crawler::source($html)->findWhereText('.apartment-options-table__sub', 'Кухня')->parent()->parent()->parent()->lastChild()->takeFloat()->castToFloat()->getResult();

        $attr['price_total_amount'] = Crawler::source($html)->find('.apartment-bar__price-value_complementary')->takeDigits()->castToInteger()->getResult();

        $attr['location_address_formatted'] = Crawler::source($html)->find('.apartment-info__sub-line_large')->removeHtmlEntities()->getResult();

        dd($attr);
    }
}

<?php

namespace App\Scraping;

require __DIR__.'/../print_facade_doc_comment.php';

use App\Scraping\Scrapers\BugrealtApartmentsScraper;

/**
 *
 */
class Test
{
    /**
     * @return void
     */
    public function __invoke()
    {
        $scraper = new BugrealtApartmentsScraper;
        echo $scraper->scrape();
    }
}

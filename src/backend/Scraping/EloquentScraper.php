<?php

namespace App\Scraping;

use GuzzleHttp\Client;
use Psr\Http\Message\UriInterface;
use Exception;
use simple_html_dom;

use function str_get_html;

/**
 *
 */
class EloquentScraper
{
    /**
     * @var string
     */
    protected $userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.97 Safari/537.36';

    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * @var int
     */
    protected $maxRequestTryings = 5;

    /**
     * @param \Psr\Http\Message\UriInterface|string $url
     * @param array $options
     *
     * @return string
     *
     * @throws \Exception
     */
    protected function getPageContent($url, $options = [])
    {
        for ($trying = 1; $trying <= $this->maxRequestTryings; $trying++) {
            try {
                $res = $this->http->get($url, $options);
                break;
            } catch (Exception $e) {
                if ($trying > $this->maxRequestTryings) {
                    throw $e;
                }

                sleep($trying * 5);
            }
        }

        return (string) $res->getBody();
    }

    /**
     * @param \Psr\Http\Message\UriInterface|string $url
     * @param array $options
     *
     * @return object
     *
     * @throws \Exception
     */
    protected function getJson($url, $options = [])
    {
        $opts = [
            'headers' => [
                'accept' => 'application/json',
            ],
        ];

        return json_decode(
            $this->getPageContent($url, array_merge($opts, $options))
        );
    }

    /**
     * @param \Psr\Http\Message\UriInterface|string $url
     * @param array $options
     *
     * @return \simple_html_dom
     *
     * @throws \Exception
     */
    protected function getHtml($url, $options = [])
    {
        $opts = [
            'headers' => [
                'accept' => 'text/html',
            ],
        ];

        return str_get_html(
            $this->getPageContent($url, array_merge($opts, $options))
        );
    }
}

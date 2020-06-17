<?php

namespace App\Scraping;

use GuzzleHttp\Client;
use Throwable;

use function str_get_html;

/**
 * @abstract
 */
abstract class EloquentScraper
{
    /**
     * @var string
     */
    protected $userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.97 Safari/537.36';

    /**
     * @var string
     */
    protected $model;

    /**
     * @var string
     */
    protected $urlKey = 'url';

    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * @var int
     */
    protected $attempts = 5;

    /**
     * @var array
     */
    protected $rules = [];

    /**
     * @var bool
     */
    protected $started = false;

    /**
     *
     */
    public function __construct()
    {
        $this->registerRules();

        $this->client = new Client([
            'headers' => [
                'user-agent' => $this->userAgent,
            ],
        ]);
    }

    /**
     * @return float
     *
     * @throws \Throwable
     */
    public function scrape()
    {
        error_reporting(E_ERROR);
        set_time_limit(0);

        $start = microtime(true);
        $this->started = true;

        $links = $this->crawleLinks();

        //

        $this->started = false;

        return microtime(true) - $start;
    }

    /**
     * @return bool
     */
    public function isStarted()
    {
        return $this->started;
    }

    /**
     * @abstract
     * @return \Illuminate\Support\Collection|string[]
     */
    abstract protected function crawleLinks();

    /**
     * @abstract
     * @return void
     */
    abstract protected function registerRules();

    /**
     * @param \Psr\Http\Message\UriInterface|string $url
     * @param array $options
     *
     * @return string
     *
     * @throws \Throwable
     */
    protected function getPageContent($url, $options = [])
    {
        for ($attempt = 1; $attempt <= $this->attempts; $attempt++) {
            try {
                $res = $this->client->get($url, $options);
                break;
            } catch (Throwable $e) {
                if ($attempt > $this->attempts) {
                    throw $e;
                }

                sleep($attempt * 5);
            }
        }

        return (string) $res->getBody();
    }

    /**
     * @param \Psr\Http\Message\UriInterface|string $url
     * @param array $options
     *
     * @return \simple_html_dom
     *
     * @throws \Throwable
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

    /**
     * @param \Psr\Http\Message\UriInterface|string $url
     * @param array $options
     *
     * @return object
     *
     * @throws \Throwable
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
}

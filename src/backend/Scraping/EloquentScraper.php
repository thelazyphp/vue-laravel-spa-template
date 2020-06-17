<?php

namespace App\Scraping;

use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use GuzzleHttp\Psr7\Uri;
use simple_html_dom;
use Throwable;
use Closure;

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
    protected $baseUrl;

    /**
     * @var array
     */
    protected $proxy = [];

    /**
     * @var array
     */
    protected $headers = [];

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

        $config = [
            'cookies' => true,
            'verify' => false,
        ];

        if (!empty($this->baseUrl)) {
            $config['base_uri'] = $this->baseUrl;
        }

        if (!empty($this->proxy)) {
            $config['proxy'] = $this->proxy;
        }

        if (!empty($this->headers)) {
            $config['headers'] = $this->headers;
        }

        if (!empty($this->userAgent)) {
            $config['headers']['user-agent'] = $this->userAgent;
        }

        $this->client = new Client($config);
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

        if (is_array($links) || !($links instanceof Collection)) {
            $links = collect($links);
        }

        foreach ($links->all() as $url) {
            //
        }

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
     * @param \GuzzleHttp\Psr7\Uri $url
     * @param \simple_html_dom $html
     * @param array $attrs
     *
     * @return void
     */
    protected function before(
        Uri $url,
        simple_html_dom $html,
        &$attrs)
    {
        //
    }

    /**
     * @param \GuzzleHttp\Psr7\Uri $url
     * @param \simple_html_dom $html
     * @param array $attrs
     *
     * @return void
     */
    protected function after(
        Uri $url,
        simple_html_dom $html,
        &$attrs)
    {
        //
    }

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

    /**
     * @param \GuzzleHttp\Psr7\Uri $url
     * @param \simple_html_dom $html
     * @param array $rules
     *
     * @return array
     */
    protected function scrapeAttrs(
        Uri $url,
        simple_html_dom $html,
        $rules)
    {
        $attrs = array_fill_keys(
            array_keys($rules), null
        );

        $this->before(
            $url,
            $html,
            $attrs
        );

        foreach ($rules as $attr => $rule) {
            if ($rule instanceof Rule) {
                $attrs[$attr] = $rule->scrape($html);
            } elseif ($rule instanceof Closure) {
                $attrs[$attr] = $rule($url, $html, $attrs);
            } elseif ($rule instanceof Relationship) {
                //
            } else {
                $attrs[$attr] = $rule;
            }
        }

        $this->after(
            $url,
            $html,
            $attrs
        );

        return $attrs;
    }
}

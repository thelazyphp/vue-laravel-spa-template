<?php

namespace App\Scraping;

use GuzzleHttp\Client;
use Throwable;
use UnexpectedValueException;
use Illuminate\Support\Collection;
use GuzzleHttp\Psr7\Uri;
use simple_html_dom;

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
    protected $urlKey = 'url';

    /**
     * @var string
     */
    protected $model;

    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * @var int
     */
    protected $attempts = 5;

    /**
     * @var bool
     */
    protected $started = false;

    /**
     * @var array
     */
    protected $rules = [];

    /**
     * @var callable[]
     */
    protected $beforeCallbacks = [];

    /**
     * @var callable[]
     */
    protected $afterCallbacks = [];

    /**
     *
     */
    public function __construct()
    {
        $this->client = new Client([
            'cookies' => true,
            'verify' => false,

            'headers' => [
                'user-agent' => $this->userAgent,
            ],
        ]);

        $this->registerRules();
    }

    /**
     * @return float
     *
     * @throws \Throwable
     * @throws \UnexpectedValueException
     */
    public function scrape()
    {
        error_reporting(0);
        set_time_limit(0);
        $this->started = true;
        $start = microtime(true);
        $links = $this->scrapeLinks();

        if (!is_array($links) && !($links instanceof Collection)) {
            throw new UnexpectedValueException(
                'Method [scrapeLinks] must return an array or an instance of [\Illuminate\Support\Collection]!'
            );
        } else if (is_array($links)) {
            $links = collect($links);
        }

        foreach (array_keys($this->rules) as $key) {
            $attributes[$key] = null;
        }

        foreach ($links as $url) {
            $model = $this->model::where($this->urlKey, $url)->first() ?: new $this->model;
            $url = new Uri($url);
            $html = $this->getHtml($url);
            $attributes[$this->urlKey] = $url->__toString();
            $this->callBeforeCallbacks($url, $html, $attributes);

            foreach ($this->rules as $attribute => $rule) {
                if ($rule instanceof Rule) {
                    $attributes[$attribute] = $rule->scrape($html);
                } else {
                    $attributes[$attribute] = $rule;
                }
            }

            $this->callAfterCallbacks($url, $html, $attributes);
            $model->fill($attributes);
            $model->save();
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
    abstract protected function scrapeLinks();

    /**
     * @abstract
     * @return void
     */
    abstract protected function registerRules();

    /**
     * @param \GuzzleHttp\Psr7\Uri $url
     * @param \simple_html_dom $html
     * @param array $attr
     *
     * @return void
     */
    protected function callBeforeCallbacks(
        Uri $url,
        simple_html_dom $html,
        $attr)
    {
        foreach ($this->beforeCallbacks as $callback) {
            $callback($url, $html, $attr);
        }
    }

    /**
     * @param \GuzzleHttp\Psr7\Uri $url
     * @param \simple_html_dom $html
     * @param array $attr
     *
     * @return void
     */
    protected function callAfterCallbacks(
        Uri $url,
        simple_html_dom $html,
        $attr)
    {
        foreach ($this->afterCallbacks as $callback) {
            $callback($url, $html, $attr);
        }
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
}

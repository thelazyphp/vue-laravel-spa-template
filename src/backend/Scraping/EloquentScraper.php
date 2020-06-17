<?php

namespace App\Scraping;

use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use GuzzleHttp\Psr7\Uri;
use Throwable;
use simple_html_dom;
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
     * @var string[]
     */
    protected $uniqueKeys = [];

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
            $url = new Uri($url);
            $html = $this->getHtml($url);

            $this->scrapeModel(
                $this->model,
                $url,
                $html,
                $this->rules,
                $this->uniqueKeys
            );
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

        foreach ($rules as $attr => $rule) {
            if ($rule instanceof Rule) {
                $attrs[$attr] = $rule->scrape($html);
            } elseif ($rule instanceof Closure) {
                $attrs[$attr] = $rule($url, $html, $attrs);
            } else {
                $attrs[$attr] = $rule;
            }
        }

        return $attrs;
    }

    /**
     * @param string $model
     * @param \GuzzleHttp\Psr7\Uri $url
     * @param \simple_html_dom $html
     * @param array $rules
     * @param string[] $uniqueKeys
     *
     * @return void
     */
    protected function scrapeModel(
        $model,
        Uri $url,
        simple_html_dom $html,
        $rules,
        $uniqueKeys = [])
    {
        $attrs = $this->scrapeAttrs(
            $url,
            $html,
            $rules
        );

        $where = [];

        foreach ($uniqueKeys as $key) {
            if (isset($attrs[$key])) {
                $where[$key] = is_array($attrs[$key])
                    ? json_encode($attrs[$key])
                    : $attrs[$key];
            }
        }

        $model = $model::firstOrNew(
            !empty($where)
                ? $where
                : [$this->urlKey => (string) $url]
        );

        foreach ($rules as $attr => $rule) {
            if ($rule instanceof Relationship) {
                $relatedAttrs = $this->scrapeAttrs(
                    $url,
                    $html,
                    $rule->getRules()
                );

                $where = [];

                foreach ($rule->getUniqueKeys() as $key) {
                    if (isset($relatedAttrs[$key])) {
                        $where[$key] = is_array($relatedAttrs[$key])
                            ? json_encode($relatedAttrs[$key])
                            : $relatedAttrs[$key];
                    }
                }

                $relatedModel = $rule->getModel();

                $relatedModel = $relatedModel::firstOrNew(
                    !empty($where)
                        ? $where
                        : [$rule->getLocalKey() => $model->{$attr}]
                );

                $relatedModel->fill($relatedAttrs);
                $relatedModel->save();

                $attrs[$attr] = $relatedModel->{$rule->getLocalKey()};
            }
        }

        $model->fill($attrs);
        $model->save();
    }
}

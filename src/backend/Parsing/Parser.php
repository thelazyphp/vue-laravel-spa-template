<?php

namespace App\Parsing;

use function str_get_html;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;

class Parser
{
    /**
     * @var string
     */
    protected $userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.138 Safari/537.36';

    /**
     * @var string
     */
    protected $model;

    /**
     * @var Client
     */
    protected $httpClient;

    /**
     * @var array
     */
    protected $httpClientConfig = [];

    /**
     *
     */
    public function __construct()
    {
        $this->httpClient = $this->makeHttpClient();
    }

    /**
     * @return void
     */
    public function run()
    {
        set_time_limit(0);

        $links = $this->parseLinks();

        if (
            !is_array($links)
            && !($links instanceof Collection)
        ) {
            throw new Exception(
                'Method [parseLinks] must return an array or an instance of [\Illuminate\Support\Collection]!'
            );
        }

        if (is_array($links)) {
            $links = new Collection($links);
        }
    }

    /**
     * @return Collection|string[]
     */
    protected function parseLinks()
    {
        return [];
    }

    /**
     * @param  string  $url
     * @return simple_html_dom
     */
    protected function getHtml($url)
    {
        return str_get_html(
            $this->getPage($url, [
                'headers' => [
                    'accept' => 'text/html',
                ],
            ])
        );
    }

    /**
     * @param  string  $url
     * @return object
     */
    protected function getJson($url)
    {
        return json_decode(
            $this->getPage($url, [
                'headers' => [
                    'accept' => 'application/json',
                ],
            ])
        );
    }

    /**
     * @param  string  $url
     * @param  array  $options
     * @return string
     */
    protected function getPage($url, $options = [])
    {
        return (string) $this->httpClient->get($url, $options)->getBody();
    }

    /**
     * @return Client
     */
    protected function makeHttpClient()
    {
        $config = [
            'headers' => [
                'user-agent' => $this->userAgent,
            ],
        ];

        return new Client(
            array_merge($config, $this->httpClientConfig)
        );
    }
}

<?php

namespace App\Parsing;

use GuzzleHttp\Client;
use function str_get_html;

class Parser
{
    /**
     * @var string
     */
    protected $userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.138 Safari/537.36';

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

    /**
     * @param  string  $url
     * @return object
     */
    protected function getJson($url)
    {
        return json_decode(
            $this->getPage($url)
        );
    }

    /**
     * @param  string  $url
     * @return simple_html_dom
     */
    protected function getHtml($url)
    {
        return str_get_html(
            $this->getPage($url)
        );
    }

    /**
     * @param  string  $url
     * @return string
     */
    protected function getPage($url)
    {
        if (!($this->httpClient instanceof Client)) {
            $this->httpClient = $this->makeHttpClient();
        }

        return (string) $this->httpClient->get($url)->getBody();
    }
}

<?php

namespace App\Parsing;

use function str_get_html;

use Exception;
use simple_html_dom;
use GuzzleHttp\Client;
use simple_html_dom_node;
use Illuminate\Support\Collection;

class Parser
{
    /**
     * @var string
     */
    protected $userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.138 Safari/537.36';

    /**
     * @var array
     */
    protected $httpClientConfig = [];

    /**
     * @var string
     */
    protected $model;

    /**
     * @var Rule[]
     */
    protected $rules = [];

    /**
     * @var bool
     */
    protected $json = false;

    /**
     * @var Client
     */
    protected $httpClient;

    /**
     *
     */
    public function __construct()
    {
        $this->registerRules();
        $this->httpClient = $this->makeHttpClient();
    }

    /**
     * @return void
     */
    public function run()
    {
        error_reporting(E_ALL);
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

        foreach ($links as $url) {
            $html = $this->getHtml($url);

            $className = $this->model;
            $model = $className::where('url', $url)->first() ?: new $className;

            foreach ($this->rules as $attribute => $rule) {
                if (!($rule instanceof Rule)) {
                    throw new Exception(
                        'Rule must be an instance of [\App\Parsing\Rule]!'
                    );
                }

                if ($rule->isDefinedAsConstValue()) {
                    $model->{$attribute} = $rule->getConstValue();
                    continue;
                }

                $value = $rule->getDefaultValue();

                if (count($rule->getFindRules())) {
                    $element = $html;

                    foreach ($rule->getFindRules() as $findRule) {
                        if (
                            $findRule['type'] == 'selector'
                            && ($element instanceof simple_html_dom || $element instanceof simple_html_dom_node)
                        ) {
                            $element = $element->find(
                                $findRule['selector'], $findRule['index']
                            );
                        } else if (
                            $findRule['type'] == 'method'
                            && ($element instanceof simple_html_dom || $element instanceof simple_html_dom_node)
                        ) {
                            $element = $element->{$findRule['method']}(...$findRule['params']);
                        } else if (strpos($findRule['type'], 'where_text') === 0) {
                            $foundElements = [];

                            foreach ($element->find($findRule['selector']) as $elem) {
                                switch ($findRule['type']) {
                                    case 'where_text':
                                        if ($elem->plaintext == $findRule['text']) {
                                            $foundElements[] = $elem;
                                        }

                                        break;
                                    case 'where_text_has':
                                        if (mb_strpos($elem->plaintext, $findRule['text']) !== false) {
                                            $foundElements[] = $elem;
                                        }

                                        break;
                                    case 'where_text_starts':
                                        if (mb_strpos($elem->plaintext, $findRule['text']) === 0) {
                                            $foundElements[] = $elem;
                                        }

                                        break;
                                    case 'where_text_ends':
                                        if (mb_strpos($elem->plaintext, $findRule['text']) === (strlen($elem->plaintext) - strlen($findRule['text']))) {
                                            $foundElements[] = $elem;
                                        }
                                }
                            }

                            if ($findRule['index'] === null) {
                                $element = $foundElements;
                            } else if ($findRule['index'] >= 0) {
                                $element = $foundElements[$findRule['index']];
                            } else {
                                $element = $foundElements[count($foundElements) - 1];
                            }
                        }
                    }

                    if ($element instanceof simple_html_dom || $element instanceof simple_html_dom_node) {
                        $value = $element->{$rule->getProperty()};
                    }
                }

                if (count($rule->getMatchRules())) {
                    if (!count($rule->getFindRules())) {
                        $value = (string) $html;
                    }

                    foreach ($rule->getMatchRules() as $matchRule) {
                        if (preg_match($matchRule['pattern'], $value, $matches)) {
                            if (isset($matches[$matchRule['group']])) {
                                $value = $matches[$matchRule['group']];
                            }
                        }
                    }
                }

                if (
                    count($rule->getReplaceRules())
                    && (count($rule->getFindRules()) || count($rule->getMatchRules()))
                ) {
                    foreach ($rule->getReplaceRules() as $replaceRule) {
                        if ($replaceRule['type'] == 'simple') {
                            $value = str_replace(
                                $replaceRule['search'], $replaceRule['replace'], $value
                            );
                        } else if ($replaceRule['type'] == 'matched') {
                            $value = preg_replace(
                                $replaceRule['pattern'], $replaceRule['replacement'], $value
                            );
                        }
                    }
                }

                $value = $rule->getPrepend().trim($value).$rule->getAppend();

                switch ($rule->getCastType()) {
                    case 'bool':
                        $value = (bool) $value;
                        break;
                    case 'int':
                        $value = (int) $value;
                        break;
                    case 'float':
                        $value = (float) str_replace(',', '.', $value);
                }

                $model->{$attribute} = $value;
            }

            echo '<pre>';
            print_r($model);
        }
    }

    /**
     * @return void
     */
    protected function registerRules()
    {
        //
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

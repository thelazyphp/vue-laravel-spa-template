<?php

namespace App\Grabbing;

use simple_html_dom;

use function str_get_html;

/**
 *
 */
class Grabber
{
    /**
     * @var string
     */
    protected $userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.106 Safari/537.36';

    /**
     *
     */
    public function __construct()
    {
        //
    }

    /**
     * @param string $model
     * @param mixed $url
     * @param array $rules
     * @param array $relationships
     *
     * @return void
     */
    public function grabModels(
        $model,
        $url,
        $rules,
        $relationships = [])
    {
        $url = collect($url);
        set_time_limit(0);

        foreach ($url->chunk(100) as $chunk) {
            foreach ($chunk->all() as $url) {
                $model = $this->grabModel(
                    $model,
                    $url,
                    $rules,
                    $relationships
                );
            }
        }
    }

    /**
     * @param string $model
     * @param string $url
     * @param array $rules
     * @param array $relationships
     *
     * @return \Illuminate\Database\Eloquent\Model|false
     */
    public function grabModel(
        $model,
        $url,
        $rules,
        $relationships = [])
    {
        $html = $this->request($url);

        if ($html === false) {
            return false;
        }

        $model = $model::updateOrCreate(
            ['url' => $url],
            $this->grabAttributes($url, $html, $rules)
        );

        foreach ($relationships as $relationship) {
            $model->{$relationship['relation']}()->create(
                $this->grabAttributes(
                    $url,
                    $html,
                    $relationship['rules']
                )
            );

            $model->save();
        }

        return $model;
    }

    /**
     * @param string $url
     * @return \simple_html_dom|false
     */
    protected function request($url)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->userAgent);

        $result = curl_exec($ch);
        curl_close($ch);

        return str_get_html($result);
    }

    /**
     * @param string $url
     * @param \simple_html_dom $html
     * @param array $rules
     *
     * @return array
     */
    protected function grabAttributes(
        $url,
        simple_html_dom $html,
        $rules)
    {
        $attributes = [];

        foreach ($rules as $attribute => $rule) {
            if ($rule instanceof Rule) {
                $attributes[$attribute] = $rule->grab($html);
            } elseif (is_callable($rule)) {
                $attributes[$attribute] = $rule(
                    $url,
                    $html,
                    $attributes
                );
            } else {
                $attributes[$attribute] = $rule;
            }
        }

        return $attributes;
    }
}

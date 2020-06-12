<?php

namespace App\Scraping;

use Illuminate\Support\Collection;
use simple_html_dom;
use simple_html_dom_node;

/**
 *
 */
class Scraper
{
    /**
     * @var \App\Scraping\Rule
     */
    protected $rule;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected $cache;

    /**
     * @param \App\Scraping\Rule $rule
     * @param \Illuminate\Support\Collection|null $cache
     */
    public function __construct(Rule $rule, ?Collection $cache = null)
    {
        $this->rule = $rule;
        $this->cache = $cache ?? collect();
    }

    /**
     * @param \simple_html_dom|\simple_html_dom_node $html
     * @param mixed $default
     *
     * @return mixed
     */
    public function scrap($html, $default = null)
    {
        $res = $html;

        foreach ($this->rule->closures() as $closure) {
            $res = call_user_func($closure, $res, $this->cache);
        }

        if (is_array($res)) {
            return array_map(function ($item) use ($default) {
                if ($item instanceof simple_html_dom || $item instanceof simple_html_dom_node) {
                    $item = trim($item->text());
                }

                return $item ?? $default;
            }, $res);
        }

        if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
            $res = trim($res->text());
        }

        return $res ?? $default;
    }
}

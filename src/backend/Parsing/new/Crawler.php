<?php

namespace App\Parsing;

use Illuminate\Support\Collection;
use simple_html_dom;
use simple_html_dom_node;

/**
 *
 */
class Crawler
{
    /**
     * @var \App\Parsing\Rule
     */
    protected $rule;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected $cache;

    /**
     * @param \App\Parsing\Rule $rule
     * @param \Illuminate\Support\Collection $cache
     */
    public function __construct(Rule $rule, Collection $cache = null)
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
    public function crawle($html, $default = null)
    {
        $res = $html;

        //

        return $res ?? $default;
    }

    /**
     * @param \simple_html_dom_node $html
     * @return string
     */
    protected function innerHtml(simple_html_dom_node $html)
    {
        return trim($html->innertext);
    }

    /**
     * @param \simple_html_dom_node $html
     * @return string
     */
    protected function outerHtml(simple_html_dom_node $html)
    {
        return trim($html->outertext);
    }

    /**
     * @param \simple_html_dom|\simple_html_dom_node $html
     * @return string
     */
    protected function innerText($html)
    {
        return trim($html->plaintext);
    }

    /**
     * @param \simple_html_dom_node[] $nodes
     * @param int|null $index
     *
     * @return \simple_html_dom_node[]|\simple_html_dom_node|null
     */
    protected function take($nodes, $index = null)
    {
        if (is_null($index)) {
            return $nodes;
        }

        if ($index < 0) {
            $index += count($nodes);
        }

        return isset($nodes[$index]) ? $nodes[$index] : null;
    }

    /**
     * @param \simple_html_dom|\simple_html_dom_node $html
     * @param string $selector
     * @param int|null $index
     *
     * @return \simple_html_dom_node[]|\simple_html_dom_node|null
     */
    protected function find(
        $html,
        $selector,
        $index = null
    ) {
        if (!$this->cache->has($selector)) {
            $res = $html->find($selector, $index);

            if (!is_null($res)) {
                $this->cache->put($selector, $res);
            }
        } else {
            $res = $this->cache->get($selector);
            $res = is_array($res) ? $this->take($res) : $res;
        }

        return $res;
    }

    /**
     * @param \simple_html_dom|\simple_html_dom_node $html
     * @param string $selector
     * @param mixed $value
     * @param int|null $index
     * @param bool $ignoreCase
     *
     * @return \simple_html_dom_node[]|\simple_html_dom_node|null
     */
    protected function findWithText(
        $html,
        $selector,
        $value,
        $index = null,
        $ignoreCase = false
    ) {
        $func = $ignoreCase ? 'strcasecmp' : 'strcmp';
        $value = (string) $value;
        $nodes = [];

        foreach ($this->find($html, $selector) as $node) {
            $text = $this->innerText($node);

            $res = call_user_func(
                $func,
                $text,
                $value
            );

            if ($res === 0) {
                $nodes[] = $node;
            }
        }

        return $this->take($nodes, $index);
    }

    /**
     * @param \simple_html_dom|\simple_html_dom_node $html
     * @param string $selector
     * @param mixed $value
     * @param int|null $index
     * @param bool $ignoreCase
     *
     * @return \simple_html_dom_node[]|\simple_html_dom_node|null
     */
    protected function findWithTextHas(
        $html,
        $selector,
        $value,
        $index = null,
        $ignoreCase = false
    ) {
        $func = $ignoreCase ? 'mb_stripos' : 'mb_strpos';
        $value = (string) $value;
        $nodes = [];

        foreach ($this->find($html, $selector) as $node) {
            $text = $this->innerText($node);

            $res = call_user_func(
                $func,
                $text,
                $value
            );

            if ($res !== false) {
                $nodes[] = $node;
            }
        }

        return $this->take($nodes, $index);
    }

    /**
     * @param \simple_html_dom|\simple_html_dom_node $html
     * @param string $selector
     * @param mixed $value
     * @param int|null $index
     * @param bool $ignoreCase
     *
     * @return \simple_html_dom_node[]|\simple_html_dom_node|null
     */
    protected function findWithTextStarts(
        $html,
        $selector,
        $value,
        $index = null,
        $ignoreCase = false
    ) {
        $func = $ignoreCase ? 'mb_stripos' : 'mb_strpos';
        $value = (string) $value;
        $nodes = [];

        foreach ($this->find($html, $selector) as $node) {
            $text = $this->innerText($node);

            $res = call_user_func(
                $func,
                $text,
                $value
            );

            if ($res === 0) {
                $nodes[] = $node;
            }
        }

        return $this->take($nodes, $index);
    }

    /**
     * @param \simple_html_dom|\simple_html_dom_node $html
     * @param string $selector
     * @param mixed $value
     * @param int|null $index
     * @param bool $ignoreCase
     *
     * @return \simple_html_dom_node[]|\simple_html_dom_node|null
     */
    protected function findWithTextEnds(
        $html,
        $selector,
        $value,
        $index = null,
        $ignoreCase = false
    ) {
        $func = $ignoreCase ? 'mb_stripos' : 'mb_strpos';
        $value = (string) $value;
        $nodes = [];

        foreach ($this->find($html, $selector) as $node) {
            $text = $this->innerText($node);
            $pos = strlen($text) - strlen($value);

            $res = call_user_func(
                $func,
                $text,
                $value
            );

            if ($res === $pos) {
                $nodes[] = $node;
            }
        }

        return $this->take($nodes, $index);
    }

    /**
     * @param \simple_html_dom|\simple_html_dom_node $html
     * @param string $selector
     * @param string $pattern
     * @param int|null $index
     *
     * @return \simple_html_dom_node[]|\simple_html_dom_node|null
     */
    protected function findWithTextMatches(
        $html,
        $selector,
        $pattern,
        $index = null
    ) {
        $nodes = [];

        foreach ($this->find($html, $selector) as $node) {
            $text = $this->innerText($node);

            if (preg_match($pattern, $text)) {
                $nodes[] = $node;
            }
        }

        return $this->take($nodes, $index);
    }

    /**
     * @param string[] $search
     * @param string[] $replace
     * @param mixed $value
     *
     * @return mixed
     */
    public function replace(
        $search,
        $replace,
        $value
    ) {
        $value = (string) $value;

        return str_replace(
            $search, $replace, $value
        );
    }

    /**
     * @param string[] $pattern
     * @param string[] $replacement
     * @param mixed $value
     *
     * @return mixed
     */
    public function replaceMatched(
        $pattern,
        $replacement,
        $value
    ) {
        $value = (string) $value;

        return preg_replace(
            $pattern, $replacement, $value
        );
    }

    /**
     * @param string $pattern
     * @param mixed $value
     * @param int $group
     *
     * @return mixed|null
     */
    protected function match(
        $pattern,
        $value,
        $group = 1
    ) {
        $value = (string) $value;

        $res = preg_match(
            $pattern,
            $value,
            $matches
        );

        return ($res && isset($matches[$group])) ? $matches[$group] : null;
    }

    /**
     * @param string $pattern
     * @param mixed $value
     * @param int $group
     *
     * @return mixed|null
     */
    protected function matchAll(
        $pattern,
        $value,
        $group = 1
    ) {
        $value = (string) $value;

        $res = preg_match_all(
            $pattern,
            $value,
            $matches
        );

        return ($res && isset($matches[$group])) ? $matches[$group] : null;
    }
}

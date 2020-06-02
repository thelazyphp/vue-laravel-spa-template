<?php

namespace App\Crawling;

use Illuminate\Support\Traits\Macroable;
use Illuminate\Support\Collection;
use simple_html_dom;
use simple_html_dom_node;
use Closure;

class Crawler
{
    protected const INNER_HTML_PROP = 'innertext';
    protected const OUTER_HTML_PROP = 'outertext';
    protected const INNER_TEXT_PROP = 'plaintext';

    use Macroable;

    /**
     * @var null|\lluminate\Support\Collection
     */
    protected $cache;

    /**
     * @var mixed
     */
    protected $result;

    /**
     * @param string|\simple_html_dom|\simple_html_dom_node $src
     * @param null|\Illuminate\Support\Collection $cache
     *
     * @return \App\Crawling\Crawler
     */
    public static function source($src, Collection $cache = null)
    {
        return new static($src, $cache);
    }

    /**
     * @param string|\simple_html_dom|\simple_html_dom_node $src
     * @param null|\Illuminate\Support\Collection $cache
     */
    public function __construct($src, Collection $cache = null)
    {
        $this->cache = $cache;
        $this->result = $src;
    }

    /**
     * @param \Illuminate\Support\Collection $cache
     *
     * @return self
     */
    public function useCache(Collection $cache)
    {
        $this->cache = $cache;
        return $this;
    }

    /**
     * @param mixed|\Closure $callback
     * @return self
     */
    public function each($callback)
    {
        $this->result = is_array($this->result) ? $this->result : (array) $this->result;

        foreach ($this->result as $key => $value) {
            $this->result[$key] = call_user_func(
                $callback, new static($value, $this->cache)
            );
        }

        return $this;
    }

    /**
     * @param mixed $default
     * @return mixed|\Illuminate\Support\Collection
     */
    public function getResult($default = null)
    {
        if (is_array($this->result)) {
            $this->each(function (Crawler $crawler) use ($default) {
                return $crawler->text()->getResult() ?? $default;
            });

            return collect($this->result);
        }

        $this->text();
        return $this->result ?? $default;
    }

    /**
     * @param string $selector
     * @param int|null $index
     *
     * @return self
     */
    public function find($selector, $index = 0)
    {
        if ($this->isHtml($this->result)) {
            $this->result = $this->querySelector($selector, $this->result, $index);
        }

        return $this;
    }

    /**
     * @param string $selector
     * @return self
     */
    public function findAll($selector)
    {
        return $this->find($selector, null);
    }

    /**
     * @param string $selector
     * @return self
     */
    public function findLast($selector)
    {
        return $this->find($selector, -1);
    }

    /**
     * @param string $selector
     * @param string $text
     * @param bool $ignoreCase
     * @param int|null $index
     *
     * @return self
     */
    public function findWhereText(
        $selector,
        $text,
        $ignoreCase = false,
        $index = 0
    ) {
        if ($this->isHtml($this->result)) {
            $function = $ignoreCase ? 'strcasecmp' : 'strcmp';
            $nodes = [];

            foreach ($this->querySelector($selector, $this->result) as $node) {
                $innerText = $this->getInnerText($node);

                if (
                    call_user_func(
                        $function, $innerText, $text
                    ) === 0
                ) {
                    $nodes[] = $node;
                }
            }

            $this->result = $this->getHtmlDomNodeByIndex($nodes, $index);
        }

        return $this;
    }

    /**
     * @param string $selector
     * @param string $text
     * @param bool $ignoreCase
     *
     * @return self
     */
    public function findAllWhereText(
        $selector,
        $text,
        $ignoreCase = false
    ) {
        return $this->findWhereText(
            $selector, $text, $ignoreCase, null
        );
    }

    /**
     * @param string $selector
     * @param string $text
     * @param bool $ignoreCase
     *
     * @return self
     */
    public function findLastWhereText(
        $selector,
        $text,
        $ignoreCase = false
    ) {
        return $this->findWhereText(
            $selector, $text, $ignoreCase, -1
        );
    }

    /**
     * @param string $selector
     * @param string $text
     * @param bool $ignoreCase
     * @param int|null $index
     *
     * @return self
     */
    public function findWhereTextContains(
        $selector,
        $text,
        $ignoreCase = false,
        $index = 0
    ) {
        if ($this->isHtml($this->result)) {
            $function = $ignoreCase ? 'mb_stripos' : 'mb_strpos';
            $nodes = [];

            foreach ($this->querySelector($selector, $this->result) as $node) {
                $innerText = $this->getInnerText($node);

                if (
                    call_user_func(
                        $function, $innerText, $text
                    ) !== false
                ) {
                    $nodes[] = $node;
                }
            }

            $this->result = $this->getHtmlDomNodeByIndex($nodes, $index);
        }

        return $this;
    }

    /**
     * @param string $selector
     * @param string $text
     * @param bool $ignoreCase
     *
     * @return self
     */
    public function findAllWhereTextContains(
        $selector,
        $text,
        $ignoreCase = false
    ) {
        return $this->findWhereTextContains(
            $selector, $text, $ignoreCase, null
        );
    }

    /**
     * @param string $selector
     * @param string $text
     * @param bool $ignoreCase
     *
     * @return self
     */
    public function findLastWhereTextContains(
        $selector,
        $text,
        $ignoreCase = false
    ) {
        return $this->findWhereTextContains(
            $selector, $text, $ignoreCase, -1
        );
    }

    /**
     * @param string $selector
     * @param string $text
     * @param bool $ignoreCase
     * @param int|null $index
     *
     * @return self
     */
    public function findWhereTextStartsWith(
        $selector,
        $text,
        $ignoreCase = false,
        $index = 0
    ) {
        if ($this->isHtml($this->result)) {
            $function = $ignoreCase ? 'mb_stripos' : 'mb_strpos';
            $nodes = [];

            foreach ($this->querySelector($selector, $this->result) as $node) {
                $innerText = $this->getInnerText($node);

                if (
                    call_user_func(
                        $function, $innerText, $text
                    ) === 0
                ) {
                    $nodes[] = $node;
                }
            }

            $this->result = $this->getHtmlDomNodeByIndex($nodes, $index);
        }

        return $this;
    }

    /**
     * @param string $selector
     * @param string $text
     * @param bool $ignoreCase
     *
     * @return self
     */
    public function findAllWhereTextStartsWith(
        $selector,
        $text,
        $ignoreCase = false
    ) {
        return $this->findWhereTextStartsWith(
            $selector, $text, $ignoreCase, null
        );
    }

    /**
     * @param string $selector
     * @param string $text
     * @param bool $ignoreCase
     *
     * @return self
     */
    public function findLastWhereTextStartsWith(
        $selector,
        $text,
        $ignoreCase = false
    ) {
        return $this->findWhereTextStartsWith(
            $selector, $text, $ignoreCase, -1
        );
    }

    /**
     * @param string $selector
     * @param string $text
     * @param bool $ignoreCase
     * @param int|null $index
     *
     * @return self
     */
    public function findWhereTextEndsWith(
        $selector,
        $text,
        $ignoreCase = false,
        $index = 0
    ) {
        if ($this->isHtml($this->result)) {
            $function = $ignoreCase ? 'mb_stripos' : 'mb_strpos';
            $nodes = [];

            foreach ($this->querySelector($selector, $this->result) as $node) {
                $innerText = $this->getInnerText($node);
                $pos = mb_strlen($innerText) - mb_strlen($text);

                if (
                    call_user_func(
                        $function, $innerText, $text
                    ) === $pos
                ) {
                    $nodes[] = $node;
                }
            }

            $this->result = $this->getHtmlDomNodeByIndex($nodes, $index);
        }

        return $this;
    }

    /**
     * @param string $selector
     * @param string $text
     * @param bool $ignoreCase
     *
     * @return self
     */
    public function findAllWhereTextEndsWith(
        $selector,
        $text,
        $ignoreCase = false
    ) {
        return $this->findWhereTextEndsWith(
            $selector, $text, $ignoreCase, null
        );
    }

    /**
     * @param string $selector
     * @param string $text
     * @param bool $ignoreCase
     *
     * @return self
     */
    public function findLastWhereTextEndsWith(
        $selector,
        $text,
        $ignoreCase = false
    ) {
        return $this->findWhereTextEndsWith(
            $selector, $text, $ignoreCase, -1
        );
    }

    /**
     * @param string $selector
     * @param string $pattern
     * @param int $capturingGroup
     * @param int|null $index
     *
     * @return self
     */
    public function findWhereTextMatches(
        $selector,
        $pattern,
        $capturingGroup = 0,
        $index = 0
    ) {
        if ($this->isHtml($this->result)) {
            $nodes = [];

            foreach ($this->querySelector($selector, $this->result) as $node) {
                $innerText = $this->getInnerText($node);

                if (preg_match($pattern, $innerText, $matches)) {
                    if (isset($matches[$capturingGroup])) {
                        $nodes[] = $node;
                    }
                }
            }

            $this->result = $this->getHtmlDomNodeByIndex($nodes, $index);
        }

        return $this;
    }

    /**
     * @param string $selector
     * @param string $pattern
     * @param int $capturingGroup
     *
     * @return self
     */
    public function findAllWhereTextMatches(
        $selector,
        $pattern,
        $capturingGroup = 0
    ) {
        return $this->findWhereTextMatches(
            $selector, $pattern, $capturingGroup, null
        );
    }

    /**
     * @param string $selector
     * @param string $pattern
     * @param int $capturingGroup
     *
     * @return self
     */
    public function findLastWhereTextMatches(
        $selector,
        $pattern,
        $capturingGroup = 0
    ) {
        return $this->findWhereTextMatches(
            $selector, $pattern, $capturingGroup, -1
        );
    }

    /**
     * @return self
     */
    public function prevSibling()
    {
        if ($this->isHtmlDomNode($this->result)) {
            $this->result = $this->result->prev_sibling();
        }

        return $this;
    }

    /**
     * @return self
     */
    public function nextSibling()
    {
        if ($this->isHtmlDomNode($this->result)) {
            $this->result = $this->result->next_sibling();
        }

        return $this;
    }

    /**
     * @return self
     */
    public function parent()
    {
        if ($this->isHtmlDomNode($this->result)) {
            $this->result = $this->result->parent();
        }

        return $this;
    }

    /**
     * @return self
     */
    public function children()
    {
        if ($this->isHtmlDomNode($this->result)) {
            $this->result = $this->result->children();
        }

        return $this;
    }

    /**
     * @param int $index
     * @return self
     */
    public function child($index)
    {
        if ($this->isHtmlDomNode($this->result)) {
            $this->result = $this->result->children($index);
        }

        return $this;
    }

    /**
     * @return self
     */
    public function firstChild()
    {
        if ($this->isHtmlDomNode($this->result)) {
            $this->result = $this->result->first_child();
        }

        return $this;
    }

    /**
     * @return self
     */
    public function lastChild()
    {
        if ($this->isHtmlDomNode($this->result)) {
            $this->result = $this->result->last_child();
        }

        return $this;
    }

    /**
     * @param string $name
     * @return self
     */
    public function attribute($name)
    {
        if ($this->isHtmlDomNode($this->result)) {
            $this->result = $this->result->{$name};
        }

        return $this;
    }

    /**
     * @return self
     */
    public function innerHtml()
    {
        if ($this->isHtmlDomNode($this->result)) {
            $this->result = $this->getInnerHtml($this->result);
        }

        return $this;
    }

    /**
     * @return self
     */
    public function outerHtml()
    {
        if ($this->isHtmlDomNode($this->result)) {
            $this->result = $this->getOuterHtml($this->result);
        }

        return $this;
    }

    /**
     * @return self
     */
    public function text()
    {
        if ($this->isHtml($this->result)) {
            $this->result = $this->getInnerText($this->result);
        }

        return $this;
    }

    /**
     * @return self
     */
    public function innerText()
    {
        return $this->text();
    }

    /**
     * @param string $pattern
     * @param int $capturingGroup
     *
     * @return self
     */
    public function match($pattern, $capturingGroup = 0)
    {
        $this->text();

        if (preg_match($pattern, $this->result, $matches)) {
            if (isset($matches[$capturingGroup])) {
                $this->result = $matches[$capturingGroup];
            }
        }

        return $this;
    }

    /**
     * @param string $pattern
     * @param int $capturingGroup
     *
     * @return self
     */
    public function matchAll($pattern, $capturingGroup = 0)
    {
        $this->text();

        if (preg_match_all($pattern, $this->result, $matches)) {
            if (isset($matches[$capturingGroup])) {
                $this->result = $matches[$capturingGroup];
            }
        }

        return $this;
    }

    /**
     * @param string $search
     * @param string $replace
     * @param bool $ignoreCase
     *
     * @return self
     */
    public function replace(
        $search,
        $replace,
        $ignoreCase = false
    ) {
        $function = $ignoreCase ? 'str_ireplace' : 'str_replace';
        $this->text();

        $this->result = call_user_func(
            $function, $search, $replace, $this->result
        );

        return $this;
    }

    /**
     * @param string $pattern
     * @param string $replacement
     *
     * @return self
     */
    public function replaceMatched($pattern, $replacement)
    {
        $this->text();

        $this->result = preg_replace(
            $pattern, $replacement, $this->result
        );

        return $this;
    }

    /**
     * @param int|null $count
     * @return self
     */
    public function takeDigits($count = null)
    {
        $this->replaceMatched('/\D/', '');

        $this->result = mb_substr(
            $this->result, 0, $count
        );

        return $this;
    }

    /**
     * @return self
     */
    public function takeInteger()
    {
        return $this->match('/(-?\d+)/', 1);
    }

    /**
     * @return self
     */
    public function takeFloat()
    {
        return $this->match('/(-?\d+(?:[.,]\d+)?)/', 1);
    }

    /**
     * @return self
     */
    public function takeNumeric()
    {
        return $this->takeFloat();
    }

    /**
     * @return self
     */
    public function removeSpaces()
    {
        return $this->replaceMatched('/\s/', '');
    }

    /**
     * @return self
     */
    public function removeHtmlEntities()
    {
        return $this->replaceMatched('/&[a-zA-Z]+;/', '');
    }

    /**
     * @param string $delimiter
     * @return self
     */
    public function explode($delimiter)
    {
        $this->text();

        $this->result = explode(
            $delimiter, $this->result
        );

        return $this;
    }

    /**
     * @param string $delimiter
     * @return self
     */
    public function implode($delimiter)
    {
        $this->result = is_array($this->result) ? $this->result : (array) $this->result;

        $this->each(function (Crawler $crawler) {
            return $crawler->text()->getResult();
        });

        $this->result = implode(
            $delimiter, $this->result
        );

        return $this;
    }

    /**
     * @return self
     */
    public function trim()
    {
        $this->text();
        $this->result = trim($this->result);

        return $this;
    }

    /**
     * @return self
     */
    public function leftTrim()
    {
        $this->text();
        $this->result = ltrim($this->result);

        return $this;
    }

    /**
     * @return self
     */
    public function rightTrim()
    {
        $this->text();
        $this->result = rtrim($this->result);

        return $this;
    }

    /**
     * @param string $value
     * @return self
     */
    public function append($value)
    {
        $this->text();
        $this->result .= $value;

        return $this;
    }

    /**
     * @param string $value
     * @return self
     */
    public function prepend($value)
    {
        $this->text();
        $this->result = $value.$this->result;

        return $this;
    }

    /**
     * @param string $type
     * @return self
     */
    public function castTo($type)
    {
        $this->text();

        switch ($type) {
            case 'bool':
            case 'boolean':
                $this->result = (bool) $this->result;
                break;
            case 'int':
            case 'integer':
                $this->result = (int) $this->result;
                break;
            case 'real':
            case 'float':
            case 'double':
                $this->result = (float) str_replace(',', '.', $this->result);
                break;
            case 'object':
                $this->result = (object) $this->result;
                break;
            case 'array':
                $this->result = is_array($this->result) ? $this->result : (array) $this->result;
        }

        return $this;
    }

    /**
     * @return self
     */
    public function castToBoolean()
    {
        return $this->castTo('bool');
    }

    /**
     * @return self
     */
    public function castToInteger()
    {
        return $this->castTo('int');
    }

    /**
     * @return self
     */
    public function castToFloat()
    {
        return $this->castTo('float');
    }

    /**
     * @return self
     */
    public function castToObject()
    {
        return $this->castTo('object');
    }

    /**
     * @return self
     */
    public function castToArray()
    {
        return $this->castTo('array');
    }

    /**
     * @return self
     */
    public function castToTimestamp()
    {
        $this->text();
        $this->result = strtotime($this->result);

        return $this;
    }

    /**
     * @param string $format
     * @return self
     */
    public function castToDateTime($format)
    {
        $this->text();

        $this->result = date(
            $format, strtotime($this->result)
        );

        return $this;
    }

    /**
     * @return bool
     */
    protected function usingCache()
    {
        return $this->cache instanceof Collection;
    }

    /**
     * @param string $selector
     * @param \simple_html_dom|\simple_html_dom_node $html
     * @param int|null $index
     *
     * @return null|\simple_html_dom_node|\simple_html_dom_node[]
     */
    protected function querySelector(
        $selector,
        $html,
        $index = null
    ) {
        if ($this->usingCache() && $this->cache->has($selector)) {
            $found = $this->cache->get($selector);
            return is_array($found) ? $this->getHtmlDomNodeByIndex($found, $index) : $found;
        }

        $found = $html->find($selector, $index);

        if ($this->usingCache() && !is_null($found)) {
            $this->cache->put($selector, $found);
        }

        return $found;
    }

    /**
     * @param \simple_html_dom_node $html
     * @return string
     */
    protected function getInnerHtml($html)
    {
        return trim($html->{self::INNER_HTML_PROP});
    }

    /**
     * @param \simple_html_dom_node $html
     * @return string
     */
    protected function getOuterHtml($html)
    {
        return trim($html->{self::OUTER_HTML_PROP});
    }

    /**
     * @param \simple_html_dom|\simple_html_dom_node $html
     * @return string
     */
    protected function getInnerText($html)
    {
        return trim($html->{self::INNER_TEXT_PROP});
    }

    /**
     * @param mixed $value
     * @return bool
     */
    protected function isHtml($value)
    {
        return $this->isHtmlDom($value) || $this->isHtmlDomNode($value);
    }

    /**
     * @param mixed $value
     * @return bool
     */
    protected function isHtmlDom($value)
    {
        return $value instanceof simple_html_dom;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    protected function isHtmlDomNode($value)
    {
        return $value instanceof simple_html_dom_node;
    }

    /**
     * @param \simple_html_dom_node[] $nodes
     * @param int|null $index
     *
     * @return null|\simple_html_dom_node|\simple_html_dom_node[]
     */
    protected function getHtmlDomNodeByIndex($nodes, $index)
    {
        if (is_null($index)) {
            return $nodes;
        }

        if ($index < 0) {
            $index = count($nodes) - 1;
        }

        return isset($nodes[$index]) ? $nodes[$index] : null;
    }
}

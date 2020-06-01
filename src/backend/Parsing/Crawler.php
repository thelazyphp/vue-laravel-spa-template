<?php

namespace App\Crawling;

use simple_html_dom;
use simple_html_dom_node;
use InvalidArgumentException;
use Illuminate\Support\Collection;

class Crawler
{
    /**
     * @var Collection
     */
    protected $cache = null;

    /**
     * @var mixed[]
     */
    protected $result = [];

    /**
     * @var bool
     */
    protected $isArray = false;

    /**
     * @param simple_html_dom|simple_html_dom_node $html
     * @param Collection|null $cache
     */
    public function __construct($html, Collection $cache = null)
    {
        if (!$this->isHtml($html)) {
            throw new InvalidArgumentException(
                'HTML must be an instance of [\simple_html_dom] or [\simple_html_dom_node]!'
            );
        }

        $this->result = (array) $html;

        if (!is_null($cache)) {
            $this->cache = $cache;
        }
    }

    /**
     * @param Collection $cache
     *
     * @return self
     */
    public function useCache(Collection $cache)
    {
        $this->cache = $cache;

        return $this;
    }

    /**
     * @param string $selector
     * @param int|null $index
     *
     * @return self
     */
    public function find($selector, $index = 0)
    {
        foreach ($this->result as $key => $value) {
            if ($this->isHtml($value)) {
                $this->result[$key] = $this->querySelector($selector, $value, $index);
            }
        }

        return $this;
    }

    /**
     * @param string $selector
     *
     * @return self
     */
    public function findAll($selector)
    {
        return $this->find($selector, null);
    }

    /**
     * @param string $selector
     *
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
        $function = $ignoreCase ? 'strcasecmp' : 'strcmp';

        foreach ($this->result as $key => $value) {
            if ($this->isHtml($value)) {
                $found = [];

                foreach ($this->querySelector($selector, $value) as $element) {
                    $innerText = $this->getInnerText($element);

                    if (
                        call_user_func(
                            $function, $innerText, $text
                        ) === 0
                    ) {
                        $found[] = $element;
                    }
                }

                $this->result[$key] = $this->getItemByIndex($found, $index);
            }
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
        $function = $ignoreCase ? 'mb_stripos' : 'mb_strpos';

        foreach ($this->result as $key => $value) {
            if ($this->isHtml($value)) {
                $found = [];

                foreach ($this->querySelector($selector, $value) as $element) {
                    $innerText = $this->getInnerText($element);

                    if (
                        call_user_func(
                            $function, $innerText, $text
                        ) !== false
                    ) {
                        $found[] = $element;
                    }
                }

                $this->result[$key] = $this->getItemByIndex($found, $index);
            }
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
        $function = $ignoreCase ? 'mb_stripos' : 'mb_strpos';

        foreach ($this->result as $key => $value) {
            if ($this->isHtml($value)) {
                $found = [];

                foreach ($this->querySelector($selector, $value) as $element) {
                    $innerText = $this->getInnerText($element);

                    if (
                        call_user_func(
                            $function, $innerText, $text
                        ) === 0
                    ) {
                        $found[] = $element;
                    }
                }

                $this->result[$key] = $this->getItemByIndex($found, $index);
            }
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
        $function = $ignoreCase ? 'mb_stripos' : 'mb_strpos';

        foreach ($this->result as $key => $value) {
            if ($this->isHtml($value)) {
                $found = [];

                foreach ($this->querySelector($selector, $value) as $element) {
                    $innerText = $this->getInnerText($element);
                    $pos = mb_strlen($innerText) - mb_strlen($text);

                    if (
                        call_user_func(
                            $function, $innerText, $text
                        ) === $pos
                    ) {
                        $found[] = $element;
                    }
                }

                $this->result[$key] = $this->getItemByIndex($found, $index);
            }
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
        foreach ($this->result as $key => $value) {
            if ($this->isHtml($value)) {
                $found = [];

                foreach ($this->querySelector($selector, $value) as $element) {
                    $innerText = $this->getInnerText($element);

                    if (preg_match($pattern, $innerText, $matches)) {
                        if (isset($matches[$capturingGroup])) {
                            $found[] = $element;
                        }
                    }
                }

                $this->result[$key] = $this->getItemByIndex($found, $index);
            }
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
        foreach ($this->result as $key => $value) {
            if ($this->isHtmlDomNode($value)) {
                $this->result[$key] = $value->prev_sibling();
            }
        }

        return $this;
    }

    /**
     * @return self
     */
    public function nextSibling()
    {
        foreach ($this->result as $key => $value) {
            if ($this->isHtmlDomNode($value)) {
                $this->result[$key] = $value->next_sibling();
            }
        }

        return $this;
    }

    /**
     * @return self
     */
    public function parent()
    {
        foreach ($this->result as $key => $value) {
            if ($this->isHtmlDomNode($value)) {
                $this->result[$key] = $value->parent();
            }
        }

        return $this;
    }

    /**
     * @return self
     */
    public function children()
    {
        foreach ($this->result as $key => $value) {
            if ($this->isHtmlDomNode($value)) {
                $this->result[$key] = $value->children();
            }
        }

        return $this;
    }

    /**
     * @param int $index
     * @return self
     */
    public function child($index)
    {
        foreach ($this->result as $key => $value) {
            if ($this->isHtmlDomNode($value)) {
                $this->result[$key] = $value->children($index);
            }
        }

        return $this;
    }

    /**
     * @return self
     */
    public function firstChild()
    {
        foreach ($this->result as $key => $value) {
            if ($this->isHtmlDomNode($value)) {
                $this->result[$key] = $value->first_child();
            }
        }

        return $this;
    }

    /**
     * @return self
     */
    public function lastChild()
    {
        foreach ($this->result as $key => $value) {
            if ($this->isHtmlDomNode($value)) {
                $this->result[$key] = $value->last_child();
            }
        }

        return $this;
    }

    /**
     * @return self
     */
    public function text()
    {
        return $this->attribute('plaintext');
    }

    /**
     * @return self
     */
    public function innerHtml()
    {
        return $this->attribute('innertext');
    }

    /**
     * @return self
     */
    public function outerHtml()
    {
        return $this->attribute('outertext');
    }

    /**
     * @param string $name
     * @return self
     */
    public function attribute($name)
    {
        foreach ($this->result as $key => $value) {
            if ($this->isHtml($value)) {
                $this->result[$key] = ($name == 'plaintext') ? $this->getInnerText($value) : $value->{$name};
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
    public function match($pattern, $capturingGroup = 0)
    {
        foreach ($this->result as $key => $value) {
            if ($this->isHtml($value)) {
                $value = $this->getInnerText($value);
            }

            if (preg_match($pattern, $value, $matches)) {
                if (isset($matches[$capturingGroup])) {
                    $this->result[$key] = $matches[$capturingGroup];
                }
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
        foreach ($this->result as $key => $value) {
            if ($this->isHtml($value)) {
                $value = $this->getInnerText($value);
            }

            if (preg_match_all($pattern, $value, $matches)) {
                if (isset($matches[$capturingGroup])) {
                    $this->result[$key] = $matches[$capturingGroup];
                }
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
    public function replace($search, $replace, $ignoreCase = false)
    {
        $function = $ignoreCase ? 'str_ireplace' : 'str_replace';

        foreach ($this->result as $key => $value) {
            if ($this->isHtml($value)) {
                $value = $this->getInnerText($value);
            }

            $this->result[$key] = call_user_func($function, $search, $replace, $value);
        }

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
        foreach ($this->result as $key => $value) {
            if ($this->isHtml($value)) {
                $value = $this->getInnerText($value);
            }

            $this->result[$key] = preg_replace($pattern, $replacement, $value);
        }

        return $this;
    }

    /**
     * @param int|null $count
     * @return self
     */
    public function takeDigits($count = null)
    {
        foreach ($this->result as $key => $value) {
            if ($this->isHtml($value)) {
                $value = $this->getInnerText($value);
            }

            $this->result[$key] = mb_substr(
                preg_replace('/\D/', '', $value), 0, $count
            );
        }

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
     * @return self
     */
    public function trim()
    {
        foreach ($this->result as $key => $value) {
            if ($this->isHtml($value)) {
                $value = $value->plaintext;
            }

            $this->result[$key] = trim($value);
        }

        return $this;
    }

    /**
     * @return self
     */
    public function leftTrim()
    {
        foreach ($this->result as $key => $value) {
            if ($this->isHtml($value)) {
                $value = $value->plaintext;
            }

            $this->result[$key] = ltrim($value);
        }

        return $this;
    }

    /**
     * @return self
     */
    public function rightTrim()
    {
        foreach ($this->result as $key => $value) {
            if ($this->isHtml($value)) {
                $value = $value->plaintext;
            }

            $this->result[$key] = rtrim($value);
        }

        return $this;
    }

    /**
     * @param string $value
     * @return self
     */
    public function append($value)
    {
        foreach ($this->result as $k => $v) {
            if ($this->isHtml($v)) {
                $v = $this->getInnerText($v);
            }

            $this->result[$k] .= $value;
        }

        return $this;
    }

    /**
     * @param string $value
     * @return self
     */
    public function prepend($value)
    {
        foreach ($this->result as $k => $v) {
            if ($this->isHtml($v)) {
                $v = $this->getInnerText($v);
            }

            $this->result[$k] = $value.$this->result[$k];
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
        foreach ($this->result as $key => $value) {
            if ($this->isHtml($value)) {
                $value = $this->getInnerText($value);
            }

            $this->result[$key] = strtotime($value);
        }

        return $this;
    }

    /**
     * @param string $format
     * @return self
     */
    public function castToDateTime($format)
    {
        foreach ($this->result as $key => $value) {
            if ($this->isHtml($value)) {
                $value = $this->getInnerText($value);
            }

            $this->result[$key] = date($format, strtotime($value));
        }

        return $this;
    }

    /**
     * @param string $type
     * @return self
     */
    public function castTo($type)
    {
        foreach ($this->result as $key => $value) {
            if ($this->isHtml($value)) {
                $value = $this->getInnerText($value);
            }

            switch ($type) {
                case 'boolean':
                case 'bool':
                    $this->result[$key] = (bool) $value;
                    break;
                case 'integer':
                case 'int':
                    $this->result[$key] = (int) $value;
                    break;
                case 'double':
                case 'float':
                case 'real':
                    $this->result[$key] = (float) str_replace(',', '.', $value);
                    break;
                case 'object':
                    $this->result[$key] = (object) $value;
                    break;
                case 'array':
                    $this->result[$key] = is_array($value) ? $value : (array) $value;
            }
        }

        return $this;
    }

    /**
     * @param string $selector
     * @param simple_html_dom|simple_html_dom_node $html
     * @param int|null $index
     *
     * @return simple_html_dom_node|simple_html_dom_node[]|null
     */
    protected function querySelector(
        $selector,
        $html,
        $index = null
    ) {
        $found = $this->retriveFromCache($selector);

        if (!is_null($found)) {
            return is_array($found) ? $this->getItemByIndex($found, $index) : $found;
        }

        $found = $html->find($selector, $index);

        if (!is_null($found)) {
            $this->putToCache($selector, $found);
        }

        return $found;
    }

    /**
     * @return bool
     */
    protected function usingCache()
    {
        return $this->cache instanceof Collection;
    }

    /**
     * @param mixed $key
     * @param mixed $value
     *
     * @return void
     */
    protected function putToCache($key, $value)
    {
        if ($this->usingCache()) {
            $this->cache->put($key, $value);
        }
    }

    /**
     * @param mixed $key
     *
     * @return bool
     */
    protected function inCache($key)
    {
        return $this->usingCache() && $this->cache->has($key);
    }

    /**
     * @param mixed $key
     * @param mixed $default
     *
     * @return mixed
     */
    protected function retriveFromCache($key, $default = null)
    {
        return $this->inCache($key) ? $this->cache->get($key, $default) : $default;
    }

    /**
     * @param mixed[] $items
     * @param int|null $index
     *
     * @return mixed|mixed[]|null
     */
    protected function getItemByIndex($items, $index)
    {
        if (is_null($index)) {
            return $items;
        }

        if ($index < 0) {
            $index = count($items) - 1;
        }

        return isset($items[$index]) ? $items[$index] : null;
    }

    /**
     * @param simple_html_dom|simple_html_dom_node $html
     *
     * @return string
     */
    protected function getInnerText($html)
    {
        return trim($html->plaintext);
    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    protected function isHtmlDomNode($value)
    {
        return $value instanceof simple_html_dom_node;
    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    protected function isHtml($value)
    {
        return $value instanceof simple_html_dom || $value instanceof simple_html_dom_node;
    }
}

<?php

namespace App\Scraping;

use Closure;
use Illuminate\Support\Collection;
use simple_html_dom;
use simple_html_dom_node;

/**
 *
 */
class Rule
{
    /**
     * @var \Closure[]
     */
    protected $closures = [];

    /**
     *
     */
    public function __construct()
    {
        //
    }

    /**
     * @param string $selector
     * @param int|null $index
     *
     * @return self
     */
    public function find($selector, $index = 0)
    {
        $this->closures[] = function ($res, $cache) use ($selector, $index) {
            if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
                return $this->findNodes($cache, $res, $selector, $index);
            }

            return $res;
        };

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
     * @param \Closure $callback
     * @return self
     */
    public function each(Closure $callback)
    {
        $this->closures[] = function ($res) use ($callback) {
            //

            return $res;
        };

        return $this;
    }

    /**
     * @param string $selector
     * @param mixed $value
     * @param int|null $index
     * @param bool $ignoreCase
     *
     * @return self
     */
    public function findWithText(
        $selector,
        $value,
        $index = 0,
        $ignoreCase = false)
    {
        $this->closures[] = function ($res, $cache) use (
            $selector,
            $value,
            $index,
            $ignoreCase)
        {
            if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
                $func = $ignoreCase ? 'strcasecmp' : 'strcmp';
                $found = [];

                foreach ($this->findNodes($cache, $res, $selector) as $node) {
                    $text = $this->getInnerText($node);

                    if (call_user_func($func, $text, $value) === 0) {
                        $found[] = $node;
                    }
                }

                return $this->getNode($found, $index);
            }

            return $res;
        };

        return $this;
    }

    /**
     * @param string $selector
     * @param mixed $value
     * @param bool $ignoreCase
     *
     * @return self
     */
    public function findAllWithText(
        $selector,
        $value,
        $ignoreCase = false)
    {
        return $this->findWithText($selector, $value, null, $ignoreCase);
    }

    /**
     * @param string $selector
     * @param mixed $value
     * @param int|null $index
     * @param bool $ignoreCase
     *
     * @return self
     */
    public function findWithTextHas(
        $selector,
        $value,
        $index = 0,
        $ignoreCase = false)
    {
        $this->closures[] = function ($res, $cache) use (
            $selector,
            $value,
            $index,
            $ignoreCase)
        {
            if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
                $func = $ignoreCase ? 'mb_stripos' : 'mb_strpos';
                $found = [];

                foreach ($this->findNodes($cache, $res, $selector) as $node) {
                    $text = $this->getInnerText($node);

                    if (call_user_func($func, $text, $value) !== false) {
                        $found[] = $node;
                    }
                }

                return $this->getNode($found, $index);
            }

            return $res;
        };

        return $this;
    }

    /**
     * @param string $selector
     * @param mixed $value
     * @param bool $ignoreCase
     *
     * @return self
     */
    public function findAllWithTextHas(
        $selector,
        $value,
        $ignoreCase = false)
    {
        return $this->findWithTextHas($selector, $value, null, $ignoreCase);
    }

    /**
     * @param string $selector
     * @param mixed $value
     * @param int|null $index
     * @param bool $ignoreCase
     *
     * @return self
     */
    public function findWithTextStarts(
        $selector,
        $value,
        $index = 0,
        $ignoreCase = false)
    {
        $this->closures[] = function ($res, $cache) use (
            $selector,
            $value,
            $index,
            $ignoreCase)
        {
            if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
                $func = $ignoreCase ? 'mb_stripos' : 'mb_strpos';
                $found = [];

                foreach ($this->findNodes($cache, $res, $selector) as $node) {
                    $text = $this->getInnerText($node);

                    if (call_user_func($func, $text, $value) === 0) {
                        $found[] = $node;
                    }
                }

                return $this->getNode($found, $index);
            }

            return $res;
        };

        return $this;
    }

    /**
     * @param string $selector
     * @param mixed $value
     * @param bool $ignoreCase
     *
     * @return self
     */
    public function findAllWithTextStarts(
        $selector,
        $value,
        $ignoreCase = false)
    {
        return $this->findWithTextStarts($selector, $value, null, $ignoreCase);
    }

    /**
     * @param string $selector
     * @param mixed $value
     * @param int|null $index
     * @param bool $ignoreCase
     *
     * @return self
     */
    public function findWithTextEnds(
        $selector,
        $value,
        $index = 0,
        $ignoreCase = false)
    {
        $this->closures[] = function ($res, $cache) use (
            $selector,
            $value,
            $index,
            $ignoreCase)
        {
            if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
                $func = $ignoreCase ? 'mb_stripos' : 'mb_strpos';
                $found = [];

                foreach ($this->findNodes($cache, $res, $selector) as $node) {
                    $text = $this->getInnerText($node);
                    $pos = mb_strlen($text) - mb_strlen($value);

                    if (call_user_func($func, $text, $value) === $pos) {
                        $found[] = $node;
                    }
                }

                return $this->getNode($found, $index);
            }

            return $res;
        };

        return $this;
    }

    /**
     * @param string $selector
     * @param mixed $value
     * @param bool $ignoreCase
     *
     * @return self
     */
    public function findAllWithTextEnds(
        $selector,
        $value,
        $ignoreCase = false)
    {
        return $this->findWithTextEnds($selector, $value, null, $ignoreCase);
    }

    /**
     * @param string $selector
     * @param string $pattern
     * @param int|null $index
     *
     * @return self
     */
    public function findWithTextMatches(
        $selector,
        $pattern,
        $index = 0)
    {
        $this->closures[] = function ($res, $cache) use (
            $selector,
            $pattern,
            $index)
        {
            if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
                $found = [];

                foreach ($this->findNodes($cache, $res, $selector) as $node) {
                    $text = $this->getInnerText($node);

                    if (preg_match($pattern, $text)) {
                        $found[] = $node;
                    }
                }

                return $this->getNode($found, $index);
            }

            return $res;
        };

        return $this;
    }

    /**
     * @param string $selector
     * @param string $pattern
     *
     * @return self
     */
    public function findAllWithTextMatches($selector, $pattern)
    {
        return $this->findWithTextMatches($selector, $pattern, null);
    }

    /**
     * @return self
     */
    public function nextSibling()
    {
        $this->closures[] = function ($res) {
            if ($res instanceof simple_html_dom_node) {
                return $res->nextSibling();
            }

            return $res;
        };

        return $this;
    }

    /**
     * @return self
     */
    public function prevSibling()
    {
        $this->closures[] = function ($res) {
            if ($res instanceof simple_html_dom_node) {
                return $res->previousSibling();
            }

            return $res;
        };

        return $this;
    }

    /**
     * @param int $levels
     * @return self
     */
    public function parent($levels = 1)
    {
        $this->closures[] = function ($res) use ($levels) {
            for ($level = 1; $level <= $levels; $level++) {
                if ($res instanceof simple_html_dom_node) {
                    $res = $res->parentNode();
                } else {
                    break;
                }
            }

            return $res;
        };

        return $this;
    }

    /**
     * @return self
     */
    public function children()
    {
        $this->closures[] = function ($res) {
            if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
                return $res->childNodes();
            }

            return $res;
        };

        return $this;
    }

    /**
     * @param int $index
     * @return self
     */
    public function child($index)
    {
        $this->closures[] = function ($res) use ($index) {
            if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
                return $res->childNodes($index);
            }

            return $res;
        };

        return $this;
    }

    /**
     * @return self
     */
    public function firstChild()
    {
        $this->closures[] = function ($res) {
            if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
                return $res->firstChild();
            }

            return $res;
        };

        return $this;
    }

    /**
     * @return self
     */
    public function lastChild()
    {
        $this->closures[] = function ($res) {
            if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
                return $res->lastChild();
            }

            return $res;
        };

        return $this;
    }

    /**
     * @return self
     */
    public function innerHtml()
    {
        return $this->closures[] = function ($res) {
            if ($res instanceof simple_html_dom_node) {
                return $this->getInnerHtml($res);
            }

            return $res;
        };

        return $this;
    }

    /**
     * @return self
     */
    public function outerHtml()
    {
        return $this->closures[] = function ($res) {
            if ($res instanceof simple_html_dom_node) {
                return $this->getOuterHtml($res);
            }

            return $res;
        };

        return $this;
    }

    /**
     * @return self
     */
    public function html()
    {
        return $this->innerHtml();
    }

    /**
     * @return self
     */
    public function innerText()
    {
        return $this->closures[] = function ($res) {
            if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
                return $this->getInnerText($res);
            }

            return $res;
        };

        return $this;
    }

    /**
     * @return self
     */
    public function text()
    {
        return $this->innerText();
    }

    /**
     * @param string|null $name
     * @return self
     */
    public function attr($name = null)
    {
        $this->closures[] = function ($res) use ($name) {
            if ($res instanceof simple_html_dom_node) {
                return is_null($name) ? $res->getAllAttributes() : $res->getAttribute($name);
            }

            return $res;
        };

        return $this;
    }

    /**
     * @param string $pattern
     * @param int $group
     * @param bool $all
     *
     * @return self
     */
    public function match(
        $pattern,
        $group = 1,
        $all = false)
    {
        $this->closures[] = function ($res) use (
            $pattern,
            $group,
            $all)
        {
            if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
                $res = $this->getInnerText($res);
            }

            $func = $all ? 'preg_match_all' : 'preg_match';
            $matches = [];

            if (call_user_func($func, $pattern, $res, $matches) && isset($matches[$group])) {
                return $matches[$group];
            }

            return $res;
        };

        return $this;
    }

    /**
     * @param string $pattern
     * @param int $group
     *
     * @return self
     */
    public function matchAll($pattern, $group = 1)
    {
        return $this->match($pattern, $group, true);
    }

    /**
     * @param string[] $value
     * @param string[] $replacement
     * @param bool $ignoreCase
     *
     * @return self
     */
    public function replace(
        $value,
        $replacement,
        $ignoreCase = false)
    {
        $this->closures[] = function ($res) use (
            $value,
            $replacement,
            $ignoreCase)
        {
            if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
                $res = $this->getInnerText($res);
            }

            $func = $ignoreCase ? 'str_ireplace' : 'str_replace';

            return call_user_func($func, $value, $replacement, $res);
        };

        return $this;
    }

    /**
     * @param string[] $pattern
     * @param string[] $replacement
     *
     * @return self
     */
    public function replaceMatches($pattern, $replacement)
    {
        $this->closures[] = function ($res) use ($pattern, $replacement) {
            if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
                $res = $this->getInnerText($res);
            }

            return preg_replace($pattern, $replacement, $res);
        };

        return $this;
    }

    /**
     * @return self
     */
    public function takeDigits()
    {
        return $this->replaceMatches('/\D/', '');
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
        return $this->match('/(-?\d+[.,]\d+)/', 1);
    }

    /**
     * @return self
     */
    public function takeNumeric()
    {
        return $this->match('/(-?\d+(?:[.,]\d+)?)/', 1);
    }

    /**
     * @return self
     */
    public function removeSpaces()
    {
        return $this->replaceMatches('/\s/', '');
    }

    /**
     * @return self
     */
    public function removeDigits()
    {
        return $this->replaceMatches('/\d/', '');
    }

    /**
     * @return self
     */
    public function removeHtmlEntities()
    {
        return $this->replaceMatches('/&(?:[A-Za-z]+|(?:#\d+));/', '');
    }

    /**
     * @return self
     */
    public function trim()
    {
        $this->closures[] = function ($res) {
            if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
                $res = $this->getInnerText($res);
            }

            return trim($res);
        };

        return $this;
    }

    /**
     * @return self
     */
    public function leftTrim()
    {
        $this->closures[] = function ($res) {
            if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
                $res = $this->getInnerText($res);
            }

            return ltrim($res);
        };

        return $this;
    }

    /**
     * @return self
     */
    public function rightTrim()
    {
        $this->closures[] = function ($res) {
            if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
                $res = $this->getInnerText($res);
            }

            return rtrim($res);
        };

        return $this;
    }

    /**
     * @param string $value
     * @return self
     */
    public function append($value)
    {
        $this->closures[] = function ($res) use ($value) {
            if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
                $res = $this->getInnerText($res);
            }

            return $res .= $value;
        };

        return $this;
    }

    /**
     * @param string $value
     * @return self
     */
    public function prepend($value)
    {
        $this->closures[] = function ($res) use ($value) {
            if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
                $res = $this->getInnerText($res);
            }

            return $value.$res;
        };

        return $this;
    }

    /**
     * @param string $type
     * @return self
     */
    public function castTo($type)
    {
        $this->closures[] = function ($res) use ($type) {
            if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
                $res = $this->getInnerText($res);
            }

            switch ($type) {
                case 'boolean':
                case 'bool':
                    return (bool) $res;
                case 'integer':
                case 'int':
                    return (int) $res;
                case 'double':
                case 'real':
                case 'float':
                    return (float) str_replace(',', '.', $res);
                case 'array':
                    return (array) $res;
                case 'object':
                    return (object) $res;
            }

            return $res;
        };

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
    public function castToArray()
    {
        return $this->castTo('array');
    }

    /**
     * @return self
     */
    public function castToObject()
    {
        return $this->castTo('object');
    }

    /**
     * @param \simple_html_dom_node $node
     * @return string
     */
    protected function getInnerHtml($node)
    {
        return trim($node->innertext());
    }

    /**
     * @param \simple_html_dom_node $node
     * @return string
     */
    protected function getOuterHtml($node)
    {
        return trim($node->outertext());
    }

    /**
     * @param \simple_html_dom|\simple_html_dom_node $html
     * @return string
     */
    protected function getInnerText($html)
    {
        return trim($html->text());
    }

    /**
     * @param \simple_html_dom_node[] $nodes
     * @param int|null $index
     *
     * @return \simple_html_dom_node[]|\simple_html_dom_node|null
     */
    protected function getNode($nodes, $index)
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
     * @param \Illuminate\Support\Collection $cache
     * @param \simple_html_dom|\simple_html_dom_node $html
     * @param string $selector
     * @param int|null $index
     *
     * @return \simple_html_dom_node[]|\simple_html_dom_node|null
     */
    protected function findNodes(
        Collection $cache,
        $html,
        $selector,
        $index = null)
    {
        if (!$cache->has($selector)) {
            $found = $html->find($selector, $index);

            if (!is_null($found)) {
                $cache->put($selector, $found);
            }
        } else {
            $found = $cache->get($selector);
            $found = is_array($found) ? $this->getNode($found, $index) : $found;
        }

        return $found;
    }
}

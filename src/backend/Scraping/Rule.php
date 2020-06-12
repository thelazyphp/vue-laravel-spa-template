<?php

namespace App\Scraping;

use Illuminate\Support\Collection;
use Closure;
use simple_html_dom;
use simple_html_dom_node;
use InvalidArgumentException;

use function str_get_html;

/**
 *
 */
class Rule
{
    /**
     * @var \Illuminate\Support\Collection
     */
    protected $cache;

    /**
     * @var \Closure[]
     */
    protected $closures = [];

    /**
     * @param \Illuminate\Support\Collection|null $cache
     */
    public function __construct(?Collection $cache = null)
    {
        $this->cache = $cache ?? collect();
    }

    /**
     * @param \simple_html_dom|\simple_html_dom_node|string $html
     * @param mixed $default
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public function scrap($html, $default = null)
    {
        if (!is_string($html) && !($html instanceof simple_html_dom) && !($html instanceof simple_html_dom_node)) {
            throw new InvalidArgumentException(
                'HTML must be a string or an instance of [\simple_html_dom] or [\simple_html_dom_node]!'
            );
        }

        if (is_string($html)) {
            $html = str_get_html($html);
        }

        $res = array_reduce($this->closures, function ($res, $closure) {
            return $closure($res);
        }, $html);

        if (is_array($res)) {
            return array_map(function ($item) use ($default) {
                if ($item instanceof simple_html_dom || $item instanceof simple_html_dom_node) {
                    $item = $this->getInnerText($item);
                }

                return $item ?? $default;
            }, $res);
        }

        if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
            $res = $this->getInnerText($res);
        }

        return $res ?? $default;
    }

    /**
     * @param callable $callback
     * @param mixed $default
     *
     * @return self
     */
    public function each(callable $callback, $default = null)
    {
        $rule = clone $this;

        $rule->closures[] = function ($res) use ($callback, $default) {
            $res = (array) $res;

            foreach ($res as $key => $value) {
                $rule = $callback($value, $key);

                if ($rule instanceof Rule) {
                    $res[$key] = $rule->scrap($value, $default);
                } else {
                    if ($rule === false) {
                        continue;
                    }

                    $res[$key] = $rule;
                }
            }

            return $res;
        };

        return $rule;
    }

    /**
     * @param string $selector
     * @param int|null $index
     *
     * @return self
     */
    public function find($selector, $index = 0)
    {
        $rule = clone $this;

        $rule->closures[] = function ($res) use ($selector, $index) {
            if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
                return $this->query($res, $selector, $index);
            }

            return $res;
        };

        return $rule;
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
        $rule = clone $this;

        $rule->closures[] = function ($res) use (
            $selector,
            $value,
            $index,
            $ignoreCase)
        {
            if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
                $func = $ignoreCase ? 'strcasecmp' : 'strcmp';
                $found = [];

                foreach ($this->query($res, $selector) as $node) {
                    $text = $this->getInnerText($node);

                    if ($func($text, $value) === 0) {
                        $found[] = $node;
                    }
                }

                return $this->getNode($found, $index);
            }

            return $res;
        };

        return $rule;
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
        $rule = clone $this;

        $rule->closures[] = function ($res) use (
            $selector,
            $value,
            $index,
            $ignoreCase)
        {
            if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
                $func = $ignoreCase ? 'mb_stripos' : 'mb_strpos';
                $found = [];

                foreach ($this->query($res, $selector) as $node) {
                    $text = $this->getInnerText($node);

                    if ($func($text, $value) !== false) {
                        $found[] = $node;
                    }
                }

                return $this->getNode($found, $index);
            }

            return $res;
        };

        return $rule;
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
        $rule = clone $this;

        $rule->closures[] = function ($res) use (
            $selector,
            $value,
            $index,
            $ignoreCase)
        {
            if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
                $func = $ignoreCase ? 'mb_stripos' : 'mb_strpos';
                $found = [];

                foreach ($this->query($res, $selector) as $node) {
                    $text = $this->getInnerText($node);

                    if ($func($text, $value) === 0) {
                        $found[] = $node;
                    }
                }

                return $this->getNode($found, $index);
            }

            return $res;
        };

        return $rule;
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
        $rule = clone $this;

        $rule->closures[] = function ($res) use (
            $selector,
            $value,
            $index,
            $ignoreCase)
        {
            if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
                $func = $ignoreCase ? 'mb_stripos' : 'mb_strpos';
                $found = [];

                foreach ($this->query($res, $selector) as $node) {
                    $text = $this->getInnerText($node);
                    $pos = mb_strlen($text) - mb_strlen($value);

                    if ($func($text, $value) === $pos) {
                        $found[] = $node;
                    }
                }

                return $this->getNode($found, $index);
            }

            return $res;
        };

        return $rule;
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
        $rule = clone $this;

        $rule->closures[] = function ($res) use (
            $selector,
            $pattern,
            $index)
        {
            if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
                $found = [];

                foreach ($this->query($res, $selector) as $node) {
                    $text = $this->getInnerText($node);

                    if (preg_match($pattern, $text)) {
                        $found[] = $node;
                    }
                }

                return $this->getNode($found, $index);
            }

            return $res;
        };

        return $rule;
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
        $rule = clone $this;

        $rule->closures[] = function ($res) {
            if ($res instanceof simple_html_dom_node) {
                return $res->nextSibling();
            }

            return $res;
        };

        return $rule;
    }

    /**
     * @return self
     */
    public function prevSibling()
    {
        $rule = clone $this;

        $rule->closures[] = function ($res) {
            if ($res instanceof simple_html_dom_node) {
                return $res->previousSibling();
            }

            return $res;
        };

        return $rule;
    }

    /**
     * @param int $levels
     * @return self
     */
    public function parent($levels = 1)
    {
        $rule = clone $this;

        $rule->closures[] = function ($res) use ($levels) {
            for ($level = 1; $level <= $levels; $level++) {
                if ($res instanceof simple_html_dom_node) {
                    $res = $res->parentNode();
                } else {
                    break;
                }
            }

            return $res;
        };

        return $rule;
    }

    /**
     * @return self
     */
    public function children()
    {
        $rule = clone $this;

        $rule->closures[] = function ($res) {
            if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
                return $res->childNodes();
            }

            return $res;
        };

        return $rule;
    }

    /**
     * @param int $index
     * @return self
     */
    public function child($index)
    {
        $rule = clone $this;

        $rule->closures[] = function ($res) use ($index) {
            if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
                return $res->childNodes($index);
            }

            return $res;
        };

        return $rule;
    }

    /**
     * @return self
     */
    public function firstChild()
    {
        $rule = clone $this;

        $rule->closures[] = function ($res) {
            if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
                return $res->firstChild();
            }

            return $res;
        };

        return $rule;
    }

    /**
     * @return self
     */
    public function lastChild()
    {
        $rule = clone $this;

        $rule->closures[] = function ($res) {
            if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
                return $res->lastChild();
            }

            return $res;
        };

        return $rule;
    }

    /**
     * @return self
     */
    public function innerHtml()
    {
        $rule = clone $this;

        return $rule->closures[] = function ($res) {
            if ($res instanceof simple_html_dom_node) {
                return $this->getInnerHtml($res);
            }

            return $res;
        };

        return $rule;
    }

    /**
     * @return self
     */
    public function outerHtml()
    {
        $rule = clone $this;

        return $rule->closures[] = function ($res) {
            if ($res instanceof simple_html_dom_node) {
                return $this->getOuterHtml($res);
            }

            return $res;
        };

        return $rule;
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
        $rule = clone $this;

        $rule->closures[] = function ($res) {
            if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
                return $this->getInnerText($res);
            }

            return $res;
        };

        return $rule;
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
        $rule = clone $this;

        $rule->closures[] = function ($res) use ($name) {
            if ($res instanceof simple_html_dom_node) {
                return is_null($name) ? $res->getAllAttributes() : $res->getAttribute($name);
            }

            return $res;
        };

        return $rule;
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
        $rule = clone $this;

        $rule->closures[] = function ($res) use (
            $pattern,
            $group,
            $all)
        {
            if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
                $res = $this->getInnerText($res);
            }

            if ($all && preg_match_all($pattern, $res, $matches) && isset($matches[$group])) {
                return $matches[$group];
            } else if (preg_match($pattern, $res, $matches) && isset($matches[$group])) {
                return $matches[$group];
            }

            return $res;
        };

        return $rule;
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
     * @param string $delim
     * @return self
     */
    public function explode($delim)
    {
        $rule = clone $this;

        $rule->closures[] = function ($res) use ($delim) {
            if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
                $res = $this->getInnerText($res);
            }

            return explode($delim, $res);
        };

        return $rule;
    }

    /**
     * @param string $pattern
     * @return self
     */
    public function regExpExplode($pattern)
    {
        $rule = clone $this;

        $rule->closures[] = function ($res) use ($pattern) {
            if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
                $res = $this->getInnerText($res);
            }

            return preg_split($pattern, $res);
        };

        return $rule;
    }

    /**
     * @param string $delim
     * @return self
     */
    public function implode($delim)
    {
        $rule = clone $this;

        $rule->closures[] = function ($res) use ($delim) {
            return implode($delim, array_map(function ($item) {
                if ($item instanceof simple_html_dom || $item instanceof simple_html_dom_node) {
                    $item = $this->getInnerText($item);
                }

                return $item;
            }, (array) $res));
        };

        return $rule;
    }

    /**
     * @param int $offset
     * @param int|null $length
     *
     * @return self
     */
    public function slice($offset, $length = null)
    {
        $rule = clone $this;

        $rule->closures[] = function ($res) use ($offset, $length) {
            return array_slice((array) $res, $offset, $length);
        };

        return $rule;
    }

    /**
     * @return self
     */
    public function sort()
    {
        $rule = clone $this;

        $rule->closures[] = function ($res) {
            sort((array) $res);

            return $res;
        };

        return $rule;
    }

    /**
     * @param callable $callback
     * @return self
     */
    public function filter(callable $callback)
    {
        $rule = clone $this;

        $rule->closures[] = function ($res) use ($callback) {
            return array_filter(array_map(function ($item) {
                if ($item instanceof simple_html_dom || $item instanceof simple_html_dom_node) {
                    $item = $this->getInnerText($item);
                }

                return $item;
            }, (array) $res), $callback);
        };

        return $rule;
    }

    /**
     * @param int $index
     * @return self
     */
    public function take($index)
    {
        $rule = clone $this;

        $rule->closures[] = function ($res) use ($index) {
            if (is_array($res)) {
                if ($index < 0) {
                    $index += count($res);
                }

                return isset($res[$index]) ? $res[$index] : null;
            }

            return $res;
        };

        return $rule;
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
        $rule = clone $this;

        $rule->closures[] = function ($res) use (
            $value,
            $replacement,
            $ignoreCase)
        {
            if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
                $res = $this->getInnerText($res);
            }

            $func = $ignoreCase ? 'str_ireplace' : 'str_replace';

            return $func($value, $replacement, $res);
        };

        return $rule;
    }

    /**
     * @param string[] $pattern
     * @param string[] $replacement
     *
     * @return self
     */
    public function regExpReplace($pattern, $replacement)
    {
        $rule = clone $this;

        $rule->closures[] = function ($res) use ($pattern, $replacement)
        {
            if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
                $res = $this->getInnerText($res);
            }

            return preg_replace($pattern, $replacement, $res);
        };

        return $rule;
    }

    /**
     * @return self
     */
    public function takeDigits()
    {
        return $this->regExpReplace('/\D/', '');
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
        return $this->regExpReplace('/\s/', '');
    }

    /**
     * @return self
     */
    public function removeDigits()
    {
        return $this->regExpReplace('/\d/', '');
    }

    /**
     * @return self
     */
    public function removeHtmlEntities()
    {
        return $this->regExpReplace('/&(?:[A-Za-z]+|(?:#\d+));/', '');
    }

    /**
     * @return self
     */
    public function trim()
    {
        $rule = clone $this;

        $rule->closures[] = function ($res) {
            if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
                $res = $this->getInnerText($res);
            }

            return trim($res);
        };

        return $rule;
    }

    /**
     * @return self
     */
    public function leftTrim()
    {
        $rule = clone $this;

        $rule->closures[] = function ($res) {
            if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
                $res = $this->getInnerText($res);
            }

            return ltrim($res);
        };

        return $rule;
    }

    /**
     * @return self
     */
    public function rightTrim()
    {
        $rule = clone $this;

        $rule->closures[] = function ($res) {
            if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
                $res = $this->getInnerText($res);
            }

            return rtrim($res);
        };

        return $rule;
    }

    /**
     * @param mixed $value
     * @return self
     */
    public function append($value)
    {
        $rule = clone $this;

        $rule->closures[] = function ($res) use ($value) {
            if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
                $res = $this->getInnerText($res);
            }

            return $res .= $value;
        };

        return $rule;
    }

    /**
     * @param mixed $value
     * @return self
     */
    public function prepend($value)
    {
        $rule = clone $this;

        $rule->closures[] = function ($res) use ($value) {
            if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
                $res = $this->getInnerText($res);
            }

            return $value.$res;
        };

        return $rule;
    }

    /**
     * @return self
     */
    public function toLowerCase()
    {
        $rule = clone $this;

        $rule->closures[] = function ($res) {
            if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
                $res = $this->getInnerText($res);
            }

            return mb_strtolower($res);
        };

        return $rule;
    }

    /**
     * @return self
     */
    public function toUpperCase()
    {
        $rule = clone $this;

        $rule->closures[] = function ($res) {
            if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
                $res = $this->getInnerText($res);
            }

            return mb_strtoupper($res);
        };

        return $rule;
    }

    /**
     * @param string $type
     * @return self
     */
    public function castTo($type)
    {
        $rule = clone $this;

        $rule->closures[] = function ($res) use ($type) {
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

        return $rule;
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
        return trim($node->innertext);
    }

    /**
     * @param \simple_html_dom_node $node
     * @return string
     */
    protected function getOuterHtml($node)
    {
        return trim($node->outertext);
    }

    /**
     * @param \simple_html_dom|\simple_html_dom_node $html
     * @return string
     */
    protected function getInnerText($html)
    {
        return trim($html->plaintext);
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
     * @param \simple_html_dom|\simple_html_dom_node $html
     * @param string $selector
     * @param int|null $index
     *
     * @return \simple_html_dom_node[]|\simple_html_dom_node|null
     */
    protected function query(
        $html,
        $selector,
        $index = null)
    {
        if (!$this->cache->has($selector)) {
            $found = $html->find($selector, $index);

            if (!is_null($found)) {
                $this->cache->put($selector, $found);
            }
        } else {
            $found = $this->cache->get($selector);
            $found = is_array($found) ? $this->getNode($found, $index) : $found;
        }

        return $found;
    }
}

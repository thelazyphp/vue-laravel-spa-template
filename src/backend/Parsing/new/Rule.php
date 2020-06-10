<?php

namespace App\Parsing;

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
     * @return \Closure[]
     */
    public function closures()
    {
        return $this->closures;
    }

    /**
     * @param string $selector
     * @param int|null $index
     *
     * @return self
     */
    public function find($selector, $index = 0)
    {
        $this->closures[] = $this->getFindClosure($selector, $index);

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
        $this->closures[] = function ($res, Collection $cache) use (
            $selector,
            $value,
            $index,
            $ignoreCase)
        {
            if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
                $func = $ignoreCase ? 'strcasecmp' : 'strcmp';
                $found = [];

                foreach ($this->getFindClosure($selector)($res, $cache) as $node) {
                    $text = trim($node->plaintext);

                    $res = call_user_func(
                        $func,
                        $text,
                        $value
                    );

                    if ($res === 0) {
                        $found[] = $node;
                    }
                }

                return $this->take($found, $index);
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
        return $this->findWithText(
            $selector,
            $value,
            null,
            $ignoreCase
        );
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
        $this->closures[] = function ($res, Collection $cache) use (
            $selector,
            $value,
            $index,
            $ignoreCase)
        {
            if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
                $func = $ignoreCase ? 'mb_stripos' : 'mb_strpos';
                $found = [];

                foreach ($this->getFindClosure($selector)($res, $cache) as $node) {
                    $text = trim($node->plaintext);

                    $res = call_user_func(
                        $func,
                        $text,
                        $value
                    );

                    if ($res !== false) {
                        $found[] = $node;
                    }
                }

                return $this->take($found, $index);
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
        return $this->findWithTextHas(
            $selector,
            $value,
            null,
            $ignoreCase
        );
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
        $this->closures[] = function ($res, Collection $cache) use (
            $selector,
            $value,
            $index,
            $ignoreCase)
        {
            if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
                $func = $ignoreCase ? 'mb_stripos' : 'mb_strpos';
                $found = [];

                foreach ($this->getFindClosure($selector)($res, $cache) as $node) {
                    $text = trim($node->plaintext);

                    $res = call_user_func(
                        $func,
                        $text,
                        $value
                    );

                    if ($res === 0) {
                        $found[] = $node;
                    }
                }

                return $this->take($found, $index);
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
        return $this->findWithTextStarts(
            $selector,
            $value,
            null,
            $ignoreCase
        );
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
        $this->closures[] = function ($res, Collection $cache) use (
            $selector,
            $value,
            $index,
            $ignoreCase)
        {
            if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
                $func = $ignoreCase ? 'mb_stripos' : 'mb_strpos';
                $found = [];

                foreach ($this->getFindClosure($selector)($res, $cache) as $node) {
                    $text = trim($node->plaintext);
                    $pos = mb_strlen($text) - mb_strlen($value);

                    $res = call_user_func(
                        $func,
                        $text,
                        $value
                    );

                    if ($res === $pos) {
                        $found[] = $node;
                    }
                }

                return $this->take($found, $index);
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
        return $this->findWithTextEnds(
            $selector,
            $value,
            null,
            $ignoreCase
        );
    }

    /**
     * @return self
     */
    public function nextSibling()
    {
        $this->closures[] = function ($res) {
            if ($res instanceof simple_html_dom_node) {
                return $res->next_sibling();
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
                return $res->prev_sibling();
            }

            return $res;
        };

        return $this;
    }

    /**
     * @return self
     */
    public function parent()
    {
        $this->closures[] = function ($res) {
            if ($res instanceof simple_html_dom_node) {
                return $res->parent();
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
            if ($res instanceof simple_html_dom_node) {
                return $res->children();
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
            if ($res instanceof simple_html_dom_node) {
                return $res->children($index);
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
            if ($res instanceof simple_html_dom_node) {
                return $res->first_child();
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
            if ($res instanceof simple_html_dom_node) {
                return $res->last_child();
            }

            return $res;
        };

        return $this;
    }

    /**
     * @param string $name
     * @return self
     */
    public function attr($name)
    {
        $this->closures[] = function ($res) use ($name) {
            if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
                return $res->{$name};
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
        return $this->attr('innertext');
    }

    /**
     * @return self
     */
    public function outerHtml()
    {
        return $this->attr('outertext');
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
        return $this->attr('plaintext');
    }

    /**
     * @return self
     */
    public function text()
    {
        return $this->innerText();
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
        $this->closures[] = function ($res) use ($pattern, $group, $all) {
            $func = $all ? 'preg_match_all' : 'preg_match';
            $matches = [];

            $res = call_user_func(
                $func,
                $pattern,
                $res,
                $matches
            );

            return ($res && isset($matches[$group])) ? $matches[$group] : null;
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
     * @param \simple_html_dom[]|\simple_html_dom_node[] $nodes
     * @param int|null $index
     *
     * @return \simple_html_dom_node[]|simple_html_dom_node|null
     */
    protected function take($nodes, $index)
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
     * @param string $selector
     * @param int|null $index
     *
     * @return \Closure
     */
    protected function getFindClosure($selector, $index = null)
    {
        return function ($res, Collection $cache) use ($selector, $index) {
            if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
                if (!$cache->has($selector)) {
                    $found = $res->find($selector, $index);

                    if (!is_null($found)) {
                        $cache->put($selector, $found);
                    }
                } else {
                    $found = $cache->get($selector);
                    $found = is_array($found) ? $this->take($found, $index) : $found;
                }

                return $found;
            }

            return $res;
        };
    }
}

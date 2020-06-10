<?php

namespace App\Parsing;

use Illuminate\Support\Collection;
use Closure;
use simple_html_dom;
use simple_html_dom_node;

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
        $this->closures[] = $this->getFindClosure($selector, $index);
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
        $index,
        $ignoreCase = false)
    {
        $this->closures[] = function ($res) use (
            $selector,
            $value,
            $index,
            $ignoreCase)
        {
            if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
                $func = $ignoreCase ? 'strcasecmp' : 'strcmp';
                $found = [];

                foreach ($this->getFindClosure($selector)($res) as $node) {
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
     * @param int|null $index
     * @param bool $ignoreCase
     *
     * @return self
     */
    public function findWithTextHas(
        $selector,
        $value,
        $index,
        $ignoreCase = false)
    {
        $this->closures[] = function ($res) use (
            $selector,
            $value,
            $index,
            $ignoreCase)
        {
            if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
                $func = $ignoreCase ? 'mb_stripos' : 'mb_strpos';
                $found = [];

                foreach ($this->getFindClosure($selector)($res) as $node) {
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
     * @param int|null $index
     * @param bool $ignoreCase
     *
     * @return self
     */
    public function findWithTextStarts(
        $selector,
        $value,
        $index,
        $ignoreCase = false)
    {
        $this->closures[] = function ($res) use (
            $selector,
            $value,
            $index,
            $ignoreCase)
        {
            if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
                $func = $ignoreCase ? 'mb_stripos' : 'mb_strpos';
                $found = [];

                foreach ($this->getFindClosure($selector)($res) as $node) {
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
     * @param int|null $index
     * @param bool $ignoreCase
     *
     * @return self
     */
    public function findWithTextEnds(
        $selector,
        $value,
        $index,
        $ignoreCase = false)
    {
        $this->closures[] = function ($res) use (
            $selector,
            $value,
            $index,
            $ignoreCase)
        {
            if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
                $func = $ignoreCase ? 'mb_stripos' : 'mb_strpos';
                $found = [];

                foreach ($this->getFindClosure($selector)($res) as $node) {
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
        return function ($res) use ($selector, $index) {
            if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
                if (!$this->cache->has($selector)) {
                    $res = $res->find($selector, $index);

                    if (!is_null($res)) {
                        $this->cache->put($selector, $res);
                    }
                } else {
                    $res = $this->cache->get($selector);
                    return is_array($res) ? $this->take($res, $index) : $res;
                }
            }

            return $res;
        };
    }
}

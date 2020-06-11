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
        $this->closures[] = function ($res, Collection $cache) use ($selector, $index) {
            if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
                return $this->findNodes($res, $cache, $selector, $index);
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

                foreach ($this->findNodes($res, $cache, $selector) as $node) {
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

                foreach ($this->findNodes($res, $cache, $selector) as $node) {
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

                foreach ($this->findNodes($res, $cache, $selector) as $node) {
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

                foreach ($this->findNodes($res, $cache, $selector) as $node) {
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
        return $this->findWithTextEnds(
            $selector,
            $value,
            null,
            $ignoreCase
        );
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
        $this->closures[] = function ($res, Collection $cache) use (
            $selector,
            $pattern,
            $index)
        {
            if ($res instanceof simple_html_dom || $res instanceof simple_html_dom_node) {
                $found = [];

                foreach ($this->findNodes($res, $cache, $selector) as $node) {
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
     * @param int $level
     * @return self
     */
    public function parent($level = 0)
    {
        $this->closures[] = function ($res) use ($level) {
            $curLevel = 0;

            while ($curLevel <= $level) {
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
                return $this->getInnerHtml($res);
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
            if ($res instanceof simple_html_dom_node) {
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
     * @param \simple_html_dom_node $node
     * @return string
     */
    protected function getInnerHtml($node)
    {
        return trim(
            $node->innertext()
        );
    }

    /**
     * @param \simple_html_dom_node $node
     * @return string
     */
    protected function getOuterHtml($node)
    {
        return trim(
            $node->outertext()
        );
    }

    /**
     * @param \simple_html_dom|\simple_html_dom_node $html
     * @return string
     */
    protected function getInnerText($html)
    {
        return trim(
            $html->text()
        );
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
     * @param \Illuminate\Support\Collection $cache
     * @param string $selector
     * @param int|null $index
     *
     * @return \simple_html_dom_node[]|\simple_html_dom_node|null
     */
    protected function findNodes(
        $html,
        Collection $cache,
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

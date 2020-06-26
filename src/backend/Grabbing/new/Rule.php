<?php

namespace App\Grabbing;

use Closure;
use App\Grabbing\DOM\ElementInterface;

/**
 *
 */
class Rule
{
    /**
     * @var Closure[]
     */
    protected $closures;

    /**
     * @param string $selector
     * @param int|null $index
     *
     * @return static
     */
    public function find($selector, $index = 0)
    {
        $rule = clone $this;

        $rule->closures[] = function ($result) use ($selector, $index) {
            if ($result instanceof ElementInterface) {
                $result = $this->takeElement(
                    $result->querySelectorAll($selector), $index
                );
            }

            return $result;
        };

        return $rule;
    }

    /**
     * @param string $selector
     * @return static
     */
    public function findAll($selector)
    {
        return $this->find($selector, null);
    }

    /**
     * @param string $selector
     * @param string $value
     * @param int|null $index
     * @param bool $ignoreCase
     *
     * @return static
     */
    public function findWhereText(
        $selector,
        $value,
        $index = 0,
        $ignoreCase = false)
    {
        $rule = clone $this;

        $rule->closures[] = function ($result) use (
            $selector,
            $value,
            $index,
            $ignoreCase)
        {
            if ($result instanceof ElementInterface) {
                $function = $ignoreCase ? 'strcasecmp' : 'strcmp';
                $elements = [];

                foreach ($result->querySelectorAll($selector) as $element) {
                    $text = trim($element->innerText());

                    if ($function($text, $value) === 0) {
                        $elements[] = $element;
                    }
                }

                $result = $this->takeElement($elements, $index);
            }

            return $result;
        };

        return $rule;
    }

    /**
     * @param string $selector
     * @param string $value
     * @param bool $ignoreCase
     *
     * @return static
     */
    public function findAllWhereText(
        $selector,
        $value,
        $ignoreCase = false)
    {
        return $this->findWhereText(
            $selector,
            $value,
            null,
            $ignoreCase,
        );
    }

    /**
     * @param string $selector
     * @param string $value
     * @param int|null $index
     * @param bool $ignoreCase
     *
     * @return static
     */
    public function findWhereTextContains(
        $selector,
        $value,
        $index = 0,
        $ignoreCase = false)
    {
        $rule = clone $this;

        $rule->closures[] = function ($result) use (
            $selector,
            $value,
            $index,
            $ignoreCase)
        {
            if ($result instanceof ElementInterface) {
                $function = $ignoreCase ? 'mb_stripos' : 'mb_strpos';
                $elements = [];

                foreach ($result->querySelectorAll($selector) as $element) {
                    $text = trim($element->innerText());

                    if ($function($text, $value) !== false) {
                        $elements[] = $element;
                    }
                }

                $result = $this->takeElement($elements, $index);
            }

            return $result;
        };

        return $rule;
    }

    /**
     * @param string $selector
     * @param string $value
     * @param bool $ignoreCase
     *
     * @return static
     */
    public function findAllWhereTextContains(
        $selector,
        $value,
        $ignoreCase = false)
    {
        return $this->findWhereTextContains(
            $selector,
            $value,
            null,
            $ignoreCase,
        );
    }

    /**
     * @param string $selector
     * @param string $pattern
     * @param int|null $index
     *
     * @return static
     */
    public function findWhereTextMatches(
        $selector,
        $pattern,
        $index = 0)
    {
        $rule = clone $this;

        $rule->closures[] = function ($result) use (
            $selector,
            $pattern,
            $index)
        {
            if ($result instanceof ElementInterface) {
                $elements = [];

                foreach ($result->querySelectorAll($selector) as $element) {
                    $text = trim($element->innerText());

                    if (preg_match($pattern, $text)) {
                        $elements[] = $element;
                    }
                }

                $result = $this->takeElement($elements, $index);
            }

            return $result;
        };

        return $rule;
    }

    /**
     * @param string $selector
     * @param string $pattern
     *
     * @return static
     */
    public function findAllWhereTextMatches($selector, $pattern)
    {
        return $this->findWhereTextMatches($selector, $pattern, null);
    }

    /**
     * @param string $selector
     * @param string $value
     * @param int|null $index
     * @param bool $ignoreCase
     *
     * @return static
     */
    public function findWhereTextStartsWith(
        $selector,
        $value,
        $index = 0,
        $ignoreCase = false)
    {
        $rule = clone $this;

        $rule->closures[] = function ($result) use (
            $selector,
            $value,
            $index,
            $ignoreCase)
        {
            if ($result instanceof ElementInterface) {
                $function = $ignoreCase ? 'mb_stripos' : 'mb_strpos';
                $elements = [];

                foreach ($result->querySelectorAll($selector) as $element) {
                    $text = trim($element->innerText());

                    if ($function($text, $value) === 0) {
                        $elements[] = $element;
                    }
                }

                $result = $this->takeElement($elements, $index);
            }

            return $result;
        };

        return $rule;
    }

    /**
     * @param string $selector
     * @param string $value
     * @param bool $ignoreCase
     *
     * @return static
     */
    public function findAllWhereTextStartsWith(
        $selector,
        $value,
        $ignoreCase = false)
    {
        return $this->findWhereTextStartsWith(
            $selector,
            $value,
            null,
            $ignoreCase,
        );
    }

    /**
     * @param string $selector
     * @param string $value
     * @param int|null $index
     * @param bool $ignoreCase
     *
     * @return static
     */
    public function findWhereTextEndsWith(
        $selector,
        $value,
        $index = 0,
        $ignoreCase = false)
    {
        $rule = clone $this;

        $rule->closures[] = function ($result) use (
            $selector,
            $value,
            $index,
            $ignoreCase)
        {
            if ($result instanceof ElementInterface) {
                $function = $ignoreCase ? 'mb_stripos' : 'mb_strpos';
                $elements = [];

                foreach ($result->querySelectorAll($selector) as $element) {
                    $text = trim($element->innerText());
                    $pos = mb_strlen($text) - mb_strlen($value);

                    if ($function($text, $value) === $pos) {
                        $elements[] = $element;
                    }
                }

                $result = $this->takeElement($elements, $index);
            }

            return $result;
        };

        return $rule;
    }

    /**
     * @param string $selector
     * @param string $value
     * @param bool $ignoreCase
     *
     * @return static
     */
    public function findAllWhereTextEndsWith(
        $selector,
        $value,
        $ignoreCase = false)
    {
        return $this->findWhereTextEndsWith(
            $selector,
            $value,
            null,
            $ignoreCase,
        );
    }

    /**
     * @param string $name
     * @return static
     */
    public function attribute($name)
    {
        $rule = clone $this;

        $rule->closures[] = function ($result) use ($name) {
            if ($result instanceof ElementInterface) {
                $result = $result->getAttribute($name);
            }

            return $result;
        };

        return $rule;
    }

    /**
     * @param string $name
     * @return static
     */
    public function attr($name)
    {
        return $this->attribute($name);
    }

    /**
     * @return static
     */
    public function outerHTML()
    {
        $rule = clone $this;

        $rule->closures[] = function ($result) {
            if ($result instanceof ElementInterface) {
                $result = trim($result->outerHTML());
            }

            return $result;
        };

        return $rule;
    }

    /**
     * @return static
     */
    public function innerHTML()
    {
        $rule = clone $this;

        $rule->closures[] = function ($result) {
            if ($result instanceof ElementInterface) {
                $result = trim($result->innerHTML());
            }

            return $result;
        };

        return $rule;
    }

    /**
     * @return static
     */
    public function html()
    {
        return $this->innerHTML();
    }

    /**
     * @return static
     */
    public function innerText()
    {
        $rule = clone $this;

        $rule->closures[] = function ($result) {
            if ($result instanceof ElementInterface) {
                $result = trim($result->innerText());
            }

            return $result;
        };

        return $rule;
    }

    /**
     * @return static
     */
    public function text()
    {
        return $this->innerText();
    }

    /**
     * @param int $levels
     * @return static
     */
    public function parent($levels = 1)
    {
        $rule = clone $this;

        $rule->closures[] = function ($result) use ($levels) {
            for ($level = 1; $level <= $levels; $level++) {
                if ($result instanceof ElementInterface) {
                    $result = $result->parentElement();
                } else {
                    break;
                }
            }

            return $result;
        };

        return $rule;
    }

    /**
     * @return static
     */
    public function children()
    {
        $rule = clone $this;

        $rule->closures[] = function ($result) {
            if ($result instanceof ElementInterface) {
                $result = $result->childElements();
            }

            return $result;
        };

        return $rule;
    }

    /**
     * @param int $index
     * @return static
     */
    public function child($index)
    {
        $rule = clone $this;

        $rule->closures[] = function ($result) use ($index) {
            if ($result instanceof ElementInterface) {
                $result = $this->takeElement(
                    $result->childElements(), $index
                );
            }

            return $result;
        };

        return $rule;
    }

    /**
     * @return static
     */
    public function firstChild()
    {
        $rule = clone $this;

        $rule->closures[] = function ($result) {
            if ($result instanceof ElementInterface) {
                $result = $result->firstChildElement();
            }

            return $result;
        };

        return $rule;
    }

    /**
     * @return static
     */
    public function lastChild()
    {
        $rule = clone $this;

        $rule->closures[] = function ($result) {
            if ($result instanceof ElementInterface) {
                $result = $result->lastChildElement();
            }

            return $result;
        };

        return $rule;
    }

    /**
     * @return static
     */
    public function previousSibling()
    {
        $rule = clone $this;

        $rule->closures[] = function ($result) {
            if ($result instanceof ElementInterface) {
                $result = $result->previousSiblingElement();
            }

            return $result;
        };

        return $rule;
    }

    /**
     * @return static
     */
    public function nextSibling()
    {
        $rule = clone $this;

        $rule->closures[] = function ($result) {
            if ($result instanceof ElementInterface) {
                $result = $result->nextSiblingElement();
            }

            return $result;
        };

        return $rule;
    }

    /**
     * @param string $pattern
     * @param int $group
     * @param bool $all
     *
     * @return static
     */
    public function match(
        $pattern,
        $group = 1,
        $all = false)
    {
        $rule = clone $this;
        $rule->innerText();

        $rule->closures[] = function ($result) use (
            $pattern,
            $group,
            $all)
        {
            if ($all) {
                if (preg_match_all($pattern, $result, $matches)) {
                    if (isset($matches[$group])) {
                        $result = $matches[$group];
                    }
                }
            } elseif (preg_match($pattern, $result, $matches)) {
                if (isset($matches[$group])) {
                    $result = $matches[$group];
                }
            }

            return $result;
        };

        return $rule;
    }

    /**
     * @param string $pattern
     * @param int $group
     *
     * @return static
     */
    public function matchAll($pattern, $group = 1)
    {
        return $this->match($pattern, $group, true);
    }

    /**
     * @param string|string[] $value
     * @param string|string[] $replacement
     * @param bool $ignoreCase
     *
     * @return static
     */
    public function replace(
        $value,
        $replacement,
        $ignoreCase = false)
    {
        $rule = clone $this;
        $rule->innerText();

        $rule->closures[] = function ($result) use (
            $value,
            $replacement,
            $ignoreCase)
        {
            return $ignoreCase
                ? str_ireplace($value, $replacement, $result)
                : str_replace($value, $replacement, $result);
        };

        return $rule;
    }

    /**
     * @param string|string[] $pattern
     * @param string|string[] $replacement
     *
     * @return static
     */
    public function replaceByPattern($pattern, $replacement)
    {
        $rule = clone $this;
        $rule->innerText();

        $rule->closures[] = function ($result) use ($pattern, $replacement) {
            return preg_replace(
                $pattern, $replacement, $result
            );
        };

        return $rule;
    }

    /**
     * @param string $pattern
     * @param callable $callback
     *
     * @return static
     */
    public function replaceCallback($pattern, callable $callback)
    {
        $rule = clone $this;
        $rule->innerText();

        $rule->closures[] = function ($result) use ($pattern, $callback) {
            return preg_replace_callback(
                $pattern, $callback, $result
            );
        };

        return $rule;
    }

    /**
     * @param ElementInterface[] $elements
     * @param int|null $index
     *
     * @return ElementInterface[]|ElementInterface|null
     */
    protected function takeElement($elements, $index = null)
    {
        if (is_null($index)) {
            return $elements;
        }

        if ($index < 0) {
            $index += count($elements);
        }

        return isset($elements[$index]) ? $elements[$index] : null;
    }
}

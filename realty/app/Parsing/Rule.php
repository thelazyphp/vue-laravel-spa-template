<?php

namespace App\Parsing;

use Closure;
use App\Parsing\DOM\Element;
use App\Parsing\DOM\Document;

class Rule
{
    /**
     * @var \Closure
     */
    protected $closures;

    /**
     * @param  \App\Parsing\DOM\Element[]  $elements
     * @param  int|null  $index
     * @return null|\App\Parsing\DOM\Element|\App\Parsing\DOM\Element[]
     */
    protected static function takeElement($elements, $index = null)
    {
        if (is_null($index)) {
            return $elements;
        }

        if ($index < 0) {
            $index += count($elements);
        }

        return isset($elements[$index]) ? $elements[$index] : null;
    }

    /**
     * @param  string  $name
     * @param  mixed  $default
     * @return self
     */
    public function attribute($name, $default = null)
    {
        $rule = clone $this;

        $rule->closures[] = function ($result) use ($name, $default) {
            if ($result instanceof Element || $result instanceof Document) {
                $attribute = $result->{$name};

                return empty($attribute)
                    ? $default
                    : $attribute;
            }

            return $result;
        };

        return $rule;
    }

    /**
     * @param  string  $name
     * @param  mixed  $default
     * @return self
     */
    public function attr($name, $default = null)
    {
        return $this->attribute($name, $default);
    }

    /**
     * @param  int  $levels
     * @return self
     */
    public function parent($levels = 1)
    {
        $rule = clone $this;

        $rule->closures[] = function ($result) use ($levels) {
            for ($level = 1; $level <= $levels; $level++) {
                if ($result instanceof Element) {
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
     * @return self
     */
    public function children()
    {
        $rule = clone $this;

        $rule->closures[] = function ($result) {
            if ($result instanceof Element || $result instanceof Document) {
                return $result->childElements();
            }

            return $result;
        };

        return $rule;
    }

    /**
     * @param  int  $index
     * @return self
     */
    public function child($index)
    {
        $rule = clone $this;

        $rule->closures[] = function ($result) use ($index) {
            if ($result instanceof Element || $result instanceof Document) {
                return static::takeElement(
                    $result->childElements(), $index
                );
            }

            return $result;
        };

        return $rule;
    }

    /**
     * @return self
     */
    public function lastChild()
    {
        $rule = clone $this;

        $rule->closures[] = function ($result) {
            if ($result instanceof Element || $result instanceof Document) {
                return $result->lastElementChild();
            }

            return $result;
        };

        return $rule;
    }

    /**
     * @return self
     */
    public function firstChild()
    {
        $rule = clone $this;

        $rule->closures[] = function ($result) {
            if ($result instanceof Element || $result instanceof Document) {
                return $result->firstElementChild();
            }

            return $result;
        };

        return $rule;
    }

    /**
     * @return self
     */
    public function nextSibling()
    {
        $rule = clone $this;

        $rule->closures[] = function ($result) {
            if ($result instanceof Element || $result instanceof Document) {
                return $result->nextElementSibling();
            }

            return $result;
        };

        return $rule;
    }

    /**
     * @return self
     */
    public function previousSibling()
    {
        $rule = clone $this;

        $rule->closures[] = function ($result) {
            if ($result instanceof Element || $result instanceof Document) {
                return $result->previousElementSibling();
            }

            return $result;
        };

        return $rule;
    }

    /**
     * @return self
     */
    public function text()
    {
        $rule = clone $this;

        $rule->closures[] = function ($result) {
            if ($result instanceof Element || $result instanceof Document) {
                return trim($result->textContent);
            }

            return $result;
        };

        return $rule;
    }

    /**
     * @return self
     */
    public function html()
    {
        $rule = clone $this;

        $rule->closures[] = function ($result) {
            if ($result instanceof Element || $result instanceof Document) {
                return trim($result->saveHTML($result));
            }

            return $result;
        };

        return $rule;
    }

    /**
     * @param  bool  $assoc
     * @param  int  $depth
     * @param  int  $options
     * @return self
     */
    public function fromJSON(
        $assoc = false,
        $depth = 512,
        $options = 0)
    {
        $rule = clone $this;

        $rule->closures[] = function ($result) use (
            $assoc,
            $depth,
            $options)
        {
            return json_decode(
                $result,
                $assoc,
                $depth,
                $options
            );
        };

        return $rule;
    }

    /**
     * @param  string|array|int|null  $key
     * @param  mixed  $default
     * @return self
     */
    public function take($key, $default = null)
    {
        $rule = clone $this;

        $rule->closures[] = function ($result) use ($key, $default) {
            return data_get(
                $result,
                $key,
                $default
            );
        };

        return $rule;
    }

    /**
     * @param  string  $pattern
     * @param  int  $group
     * @return self
     */
    public function match($pattern, $group = 0)
    {
        $rule = clone $this;

        $rule->closures[] = function ($result) use ($pattern, $group) {
            if ($result instanceof Element || $result instanceof Document) {
                $result = trim($result->textContent);
            }

            if (preg_match($pattern, $result, $matches)) {
                if (isset($matches[$group])) {
                    return $matches[$group];
                }
            }

            return $result;
        };

        return $rule;
    }

    /**
     * @param  string  $pattern
     * @param  int  $group
     * @return self
     */
    public function matchAll($pattern, $group = 0)
    {
        $rule = clone $this;

        $rule->closures[] = function ($result) use ($pattern, $group) {
            if ($result instanceof Element || $result instanceof Document) {
                $result = trim($result->textContent);
            }

            if (preg_match_all($pattern, $result, $matches)) {
                if (isset($matches[$group])) {
                    return $matches[$group];
                }
            }

            return $result;
        };

        return $rule;
    }

    /**
     * @param  string|string[]  $search
     * @param  string|string[]  $replace
     * @param  bool  $ignoreCase
     * @return self
     */
    public function replace($search, $replace, $ignoreCase = false)
    {
        $rule = clone $this;

        $rule->closures[] = function ($result) use (
            $search,
            $replace,
            $ignoreCase)
        {
            if ($result instanceof Element || $result instanceof Document) {
                $result = trim($result->textContent);
            }

            $function = $ignoreCase ? 'str_ireplace' : 'str_replace';

            return $function(
                $search, $replace, $result
            );
        };

        return $rule;
    }

    /**
     * @param  string|string[]  $pattern
     * @param  string|string[]  $replacement
     * @return self
     */
    public function pregReplace($pattern, $replacement)
    {
        $rule = clone $this;

        $rule->closures[] = function ($result) use ($pattern, $replacement) {
            if ($result instanceof Element || $result instanceof Document) {
                $result = trim($result->textContent);
            }

            return preg_replace(
                $pattern, $replacement, $result
            );
        };

        return $rule;
    }

    /**
     * @param  string|string[]  $regex
     * @param  callable  $callback
     * @return self
     */
    public function pregReplaceCallback($regex, callable $callback)
    {
        $rule = clone $this;

        $rule->closures[] = function ($result) use ($regex, $callback) {
            if ($result instanceof Element || $result instanceof Document) {
                $result = trim($result->textContent);
            }

            return preg_replace_callback(
                $regex, $callback, $result
            );
        };

        return $rule;
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
    public function takeDigits()
    {
        return $this->pregReplace('/\D/', '');
    }

    /**
     * @return self
     */
    public function removeSpaces()
    {
        return $this->pregReplace('/\s/', '');
    }

    /**
     * @return self
     */
    public function removeHTMLEntities()
    {
        return $this->pregReplace('/&(?:[a-zA-Z]|(?:#\d+));/', '');
    }

    /**
     * @param  string  $selector
     * @param  int|null  $index
     * @return self
     */
    public function find($selector, $index = 0)
    {
        $rule = clone $this;

        $rule->closures[] = function ($result) use ($selector, $index) {
            if ($result instanceof Element || $result instanceof Document) {
                return static::takeElement(
                    $result->querySelectorAll($selector), $index
                );
            }

            return $result;
        };

        return $rule;
    }

    /**
     * @param  string  $selector
     * @return self
     */
    public function findAll($selector)
    {
        return $this->find($selector, null);
    }

    /**
     * @param  string  $selector
     * @param  string  $value
     * @param  int|null  $index
     * @param  bool  $ignoreCase
     * @return self
     */
    public function findWhereText(
        $selector,
        $value,
        $index = null,
        $ignoreCase = false)
    {
        $rule = clone $this;

        $rule->closures[] = function ($result) use (
            $selector,
            $value,
            $index,
            $ignoreCase)
        {
            if ($result instanceof Element || $result instanceof Document) {
                $elements = [];

                foreach ($result->querySelectorAll($selector) as $element) {
                    $text = trim($element->textContent);
                    $function = $ignoreCase ? 'mb_strcasecmp' : 'mb_strcmp';

                    if ($function($text, $value) === 0) {
                        $elements[] = $element;
                    }
                }

                return static::takeElement($elements, $index);
            }

            return $result;
        };

        return $rule;
    }

    /**
     * @param  string  $selector
     * @param  string  $pattern
     * @param  int|null  $index
     * @return self
     */
    public function findWhereTextMatches(
        $selector,
        $pattern,
        $index = null)
    {
        $rule = clone $this;

        $rule->closures[] = function ($result) use (
            $selector,
            $pattern,
            $index)
        {
            if ($result instanceof Element || $result instanceof Document) {
                $elements = [];

                foreach ($result->querySelectorAll($selector) as $element) {
                    $text = trim($element->textContent);

                    if (preg_match($pattern, $text)) {
                        $elements[] = $element;
                    }
                }

                return static::takeElement($elements, $index);
            }

            return $result;
        };

        return $rule;
    }

    /**
     * @param  string  $selector
     * @param  string  $value
     * @param  int|null  $index
     * @param  bool  $ignoreCase
     * @return self
     */
    public function findWhereTextContains(
        $selector,
        $value,
        $index = null,
        $ignoreCase = false)
    {
        $rule = clone $this;

        $rule->closures[] = function ($result) use (
            $selector,
            $value,
            $index,
            $ignoreCase)
        {
            if ($result instanceof Element || $result instanceof Document) {
                $elements = [];

                foreach ($result->querySelectorAll($selector) as $element) {
                    $text = trim($element->textContent);
                    $function = $ignoreCase ? 'mb_stripos' : 'mb_strpos';

                    if ($function($text, $value) !== false) {
                        $elements[] = $element;
                    }
                }

                return static::takeElement($elements, $index);
            }

            return $result;
        };

        return $rule;
    }

    /**
     * @param  string  $selector
     * @param  string  $value
     * @param  int|null  $index
     * @param  bool  $ignoreCase
     * @return self
     */
    public function findWhereTextEndsWith(
        $selector,
        $value,
        $index = null,
        $ignoreCase = false)
    {
        $rule = clone $this;

        $rule->closures[] = function ($result) use (
            $selector,
            $value,
            $index,
            $ignoreCase)
        {
            if ($result instanceof Element || $result instanceof Document) {
                $elements = [];

                foreach ($result->querySelectorAll($selector) as $element) {
                    $text = trim($element->textContent);
                    $pos = mb_strlen($text) - mb_strlen($value);
                    $function = $ignoreCase ? 'mb_stripos' : 'mb_strpos';

                    if ($function($text, $value) === $pos) {
                        $elements[] = $element;
                    }
                }

                return static::takeElement($elements, $index);
            }

            return $result;
        };

        return $rule;
    }

    /**
     * @param  string  $selector
     * @param  string  $value
     * @param  int|null  $index
     * @param  bool  $ignoreCase
     * @return self
     */
    public function findWhereTextStartsWith(
        $selector,
        $value,
        $index = null,
        $ignoreCase = false)
    {
        $rule = clone $this;

        $rule->closures[] = function ($result) use (
            $selector,
            $value,
            $index,
            $ignoreCase)
        {
            if ($result instanceof Element || $result instanceof Document) {
                $elements = [];

                foreach ($result->querySelectorAll($selector) as $element) {
                    $text = trim($element->textContent);
                    $function = $ignoreCase ? 'mb_stripos' : 'mb_strpos';

                    if ($function($text, $value) === 0) {
                        $elements[] = $element;
                    }
                }

                return static::takeElement($elements, $index);
            }

            return $result;
        };

        return $rule;
    }
}

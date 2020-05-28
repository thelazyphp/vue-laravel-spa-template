<?php

namespace App\Crawling;

use simple_html_dom;
use simple_html_dom_node;
use InvalidArgumentException;

class Crawler
{
    /**
     * @var mixed
     */
    protected $result;

    /**
     * @var mixed
     */
    protected $default;

    /**
     * @param  simple_html_dom|simple_html_dom_node  $html
     * @param  mixed  $default
     */
    public function __construct($html, $default = null)
    {
        if (
            !($html instanceof simple_html_dom_node)
            && !($html instanceof simple_html_dom)
        ) {
            throw new InvalidArgumentException(
                'HTML must be an instance of [\simple_html_dom] or an instance of [\simple_html_dom_node]!'
            );
        }

        $this->result = $html;
        $this->default = $default;
    }

    /**
     * @param  string  $selector
     * @param  int|null  $index
     * @return self
     */
    public function find($selector, $index = 0)
    {
        if (
            $this->result instanceof simple_html_dom_node
            || $this->result instanceof simple_html_dom
        ) {
            $this->result = $this->result->find($selector, $index);
        }

        return $this;
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
     * @return self
     */
    public function findLast($selector)
    {
        return $this->find($selector, -1);
    }

    /**
     * @param  string  $selector
     * @param  string  $text
     * @param  bool  $caseSensitive
     * @param  int|null  $index
     * @return self
     */
    public function findWhereText(
        $selector,
        $text,
        $caseSensitive = true,
        $index = 0
    ) {
        if (
            $this->result instanceof simple_html_dom_node
            || $this->result instanceof simple_html_dom
        ) {
            $function = $caseSensitive ? 'strcasecmp' : 'strcmp';
            $found = [];

            foreach ($this->result->find($selector) as $element) {
                $result = call_user_func(
                    $function, $element->plaintext, $text
                );

                if ($result === 0) {
                    $found[] = $element;
                }
            }

            $this->result = $index === null
                ? $found
                : (count($found) ? ($index >= 0 ? $found[$index] : $found[count($found) - 1]) : null);
        }

        return $this;
    }

    /**
     * @param  string  $selector
     * @param  string  $text
     * @param  bool  $caseSensitive
     * @return self
     */
    public function findAllWhereText(
        $selector,
        $text,
        $caseSensitive = true
    ) {
        return $this->findWhereText(
            $selector, $text, $caseSensitive, null
        );
    }

    /**
     * @param  string  $selector
     * @param  string  $text
     * @param  bool  $caseSensitive
     * @return self
     */
    public function findLastWhereText(
        $selector,
        $text,
        $caseSensitive = true
    ) {
        return $this->findWhereText(
            $selector, $text, $caseSensitive, -1
        );
    }

    /**
     * @param  string  $selector
     * @param  string  $text
     * @param  bool  $caseSensitive
     * @param  int|null  $index
     * @return self
     */
    public function findWhereTextHas(
        $selector,
        $text,
        $caseSensitive = true,
        $index = 0
    ) {
        if (
            $this->result instanceof simple_html_dom_node
            || $this->result instanceof simple_html_dom
        ) {
            $function = $caseSensitive ? 'mb_stripos' : 'mb_strpos';
            $found = [];

            foreach ($this->result->find($selector) as $element) {
                $result = call_user_func(
                    $function, $element->plaintext, $text
                );

                if ($result !== false) {
                    $found[] = $element;
                }
            }

            $this->result = $index === null
                ? $found
                : (count($found) ? ($index >= 0 ? $found[$index] : $found[count($found) - 1]) : null);
        }

        return $this;
    }

    /**
     * @param  string  $selector
     * @param  string  $text
     * @param  bool  $caseSensitive
     * @return self
     */
    public function findAllWhereTextHas(
        $selector,
        $text,
        $caseSensitive = true
    ) {
        return $this->findWhereTextHas(
            $selector, $text, $caseSensitive, null
        );
    }

    /**
     * @param  string  $selector
     * @param  string  $text
     * @param  bool  $caseSensitive
     * @return self
     */
    public function findLastWhereTextHas(
        $selector,
        $text,
        $caseSensitive = true
    ) {
        return $this->findWhereTextHas(
            $selector, $text, $caseSensitive, -1
        );
    }

    /**
     * @param  string  $selector
     * @param  string  $text
     * @param  bool  $caseSensitive
     * @param  int|null  $index
     * @return self
     */
    public function findWhereTextStartsWith(
        $selector,
        $text,
        $caseSensitive = true,
        $index = 0
    ) {
        if (
            $this->result instanceof simple_html_dom_node
            || $this->result instanceof simple_html_dom
        ) {
            $function = $caseSensitive ? 'mb_stripos' : 'mb_strpos';
            $found = [];

            foreach ($this->result->find($selector) as $element) {
                $result = call_user_func(
                    $function, $element->plaintext, $text
                );

                if ($result === 0) {
                    $found[] = $element;
                }
            }

            $this->result = $index === null
                ? $found
                : (count($found) ? ($index >= 0 ? $found[$index] : $found[count($found) - 1]) : null);
        }

        return $this;
    }

    /**
     * @param  string  $selector
     * @param  string  $text
     * @param  bool  $caseSensitive
     * @return self
     */
    public function findAllWhereTextStartsWith(
        $selector,
        $text,
        $caseSensitive = true
    ) {
        return $this->findWhereTextStartsWith(
            $selector, $text, $caseSensitive, null
        );
    }

    /**
     * @param  string  $selector
     * @param  string  $text
     * @param  bool  $caseSensitive
     * @return self
     */
    public function findLastWhereTextStartsWith(
        $selector,
        $text,
        $caseSensitive = true
    ) {
        return $this->findWhereTextStartsWith(
            $selector, $text, $caseSensitive, -1
        );
    }

    /**
     * @param  string  $selector
     * @param  string  $text
     * @param  bool  $caseSensitive
     * @param  int|null  $index
     * @return self
     */
    public function findWhereTextEndsWith(
        $selector,
        $text,
        $caseSensitive = true,
        $index = 0
    ) {
        if (
            $this->result instanceof simple_html_dom_node
            || $this->result instanceof simple_html_dom
        ) {
            $function = $caseSensitive ? 'mb_stripos' : 'mb_strpos';
            $found = [];

            foreach ($this->result->find($selector) as $element) {
                $result = call_user_func(
                    $function, $element->plaintext, $text
                );

                $pos = strlen($element->plaintext) - strlen($text);

                if ($result === $pos) {
                    $found[] = $element;
                }
            }

            $this->result = $index === null
                ? $found
                : (count($found) ? ($index >= 0 ? $found[$index] : $found[count($found) - 1]) : null);
        }

        return $this;
    }

    /**
     * @param  string  $selector
     * @param  string  $text
     * @param  bool  $caseSensitive
     * @return self
     */
    public function findAllWhereTextEndsWith(
        $selector,
        $text,
        $caseSensitive = true
    ) {
        return $this->findWhereTextEndsWith(
            $selector, $text, $caseSensitive, null
        );
    }

    /**
     * @param  string  $selector
     * @param  string  $text
     * @param  bool  $caseSensitive
     * @return self
     */
    public function findLastWhereTextEndsWith(
        $selector,
        $text,
        $caseSensitive = true
    ) {
        return $this->findWhereTextEndsWith(
            $selector, $text, $caseSensitive, -1
        );
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
        $index = 0
    ) {
        if (
            $this->result instanceof simple_html_dom_node
            || $this->result instanceof simple_html_dom
        ) {
            $found = [];

            foreach ($this->result->find($selector) as $element) {
                if (preg_match($pattern, $element->plaintext)) {
                    $found[] = $element;
                }
            }

            $this->result = $index === null
                ? $found
                : (count($found) ? ($index >= 0 ? $found[$index] : $found[count($found) - 1]) : null);
        }

        return $this;
    }

    /**
     * @param  string  $selector
     * @param  string  $pattern
     * @return self
     */
    public function findAllWhereTextMatches($selector, $pattern) {
        return $this->findWhereTextMatches(
            $selector, $pattern, null
        );
    }

    /**
     * @param  string  $selector
     * @param  string  $pattern
     * @return self
     */
    public function findLastWhereTextMatches($selector, $pattern) {
        return $this->findWhereTextMatches(
            $selector, $pattern, -1
        );
    }

    /**
     * @return self
     */
    public function prevSibling()
    {
        if ($this->result instanceof simple_html_dom_node) {
            $this->result = $this->result->prev_sibling();
        }

        return $this;
    }

    /**
     * @return self
     */
    public function nextSibling()
    {
        if ($this->result instanceof simple_html_dom_node) {
            $this->result = $this->result->next_sibling();
        }

        return $this;
    }

    /**
     * @return self
     */
    public function parent()
    {
        if ($this->result instanceof simple_html_dom_node) {
            $this->result = $this->result->parent();
        }

        return $this;
    }

    /**
     * @return self
     */
    public function children()
    {
        if ($this->result instanceof simple_html_dom_node) {
            $this->result = $this->result->children();
        }

        return $this;
    }

    /**
     * @param  int  $index
     * @return self
     */
    public function child($index)
    {
        if ($this->result instanceof simple_html_dom_node) {
            $this->result = $this->result->children($index);
        }

        return $this;
    }

    /**
     * @return self
     */
    public function firstChild()
    {
        if ($this->result instanceof simple_html_dom_node) {
            $this->result = $this->result->first_child();
        }

        return $this;
    }

    /**
     * @return self
     */
    public function lastChild()
    {
        if ($this->result instanceof simple_html_dom_node) {
            $this->result = $this->result->last_child();
        }

        return $this;
    }

    /**
     * @param  string  $name
     * @return self
     */
    public function getAttribute($name)
    {
        if (
            $this->result instanceof simple_html_dom_node
            || $this->result instanceof simple_html_dom
        ) {
            $this->result = $this->result->{$name};
        }

        return $this;
    }

    /**
     * @return self
     */
    public function text()
    {
        return $this->getAttribute('plaintext');
    }

    /**
     * @return self
     */
    public function innerHtml()
    {
        return $this->getAttribute('innertext');
    }

    /**
     * @return self
     */
    public function outerHtml()
    {
        return $this->getAttribute('outertext');
    }

    /**
     * @param  string  $pattern
     * @param  int  $capturingGroup
     * @return self
     */
    public function match($pattern, $capturingGroup = 0)
    {
        if (preg_match($pattern, $this->result, $matches)) {
            if (isset($matches[$capturingGroup])) {
                $this->result = $matches[$capturingGroup];
            }
        }

        return $this;
    }

    /**
     * @param  string  $pattern
     * @param  int  $capturingGroup
     * @return self
     */
    public function matchAll($pattern, $capturingGroup = 0)
    {
        if (preg_match_all($pattern, $this->result, $matches)) {
            $result = [];

            foreach ($matches as $match) {
                if (isset($match[$capturingGroup])) {
                    $result[] = $match[$capturingGroup];
                }
            }

            $this->result = $result;
        }

        return $this;
    }

    /**
     * @param  string|string[]  $search
     * @param  string|string[]  $replace
     * @return self
     */
    public function replace($search, $replace)
    {
        $this->result = str_replace(
            $search, $replace, $this->result
        );

        return $this;
    }

    /**
     * @param  string|string[]  $pattern
     * @param  string|string[]  $replacement
     * @return self
     */
    public function replaceMatched($pattern, $replacement)
    {
        $this->result = preg_replace(
            $pattern, $replacement, $this->result
        );

        return $this;
    }

    /**
     * @param  int|null  $count
     * @return self
     */
    public function takeDigits($count = null)
    {
        $this->replaceMatched('/\D/', '');

        $this->result = substr(
            $this->result, 0, $count
        );

        return $this;
    }

    /**
     * @return self
     */
    public function takeInteger()
    {
        return $this->match('/(\d+)/', 1);
    }

    /**
     * @return self
     */
    public function takeFloat()
    {
        return $this->match('/(\d+(?:[.,]\d+)?)/', 1);
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
     * @param  string  $value
     * @return self
     */
    public function append($value)
    {
        $this->result .= $value;
        return $this;
    }

    /**
     * @param  string  $value
     * @return self
     */
    public function prepend($value)
    {
        $this->result = $value.$this->result;
        return $this;
    }

    /**
     * @return self
     */
    public function toLowerCase()
    {
        $this->result = mb_strtolower($this->result);
        return $this;
    }

    /**
     * @return self
     */
    public function toUpperCase()
    {
        $this->result = mb_strtoupper($this->result);
        return $this;
    }

    /**
     * @param  string  $type
     * @return self
     */
    public function castTo($type)
    {
        switch ($type) {
            case 'boolean':
            case 'bool':
                $this->result = (bool) $this->result;
                break;
            case 'integer':
            case 'int':
                $this->result = (int) $this->result;
                break;
            case 'double':
            case 'float':
            case 'real':
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
        //

        return $this;
    }

    /**
     * @param  string  $format
     * @return self
     */
    public function castToDateTime($format)
    {
        //

        return $this;
    }

    /**
     * @param  mixed  $default
     * @return mixed
     */
    public function get($default = null)
    {
        $default = $default ?? $this->default;
        return $this->result ?? $default;
    }
}

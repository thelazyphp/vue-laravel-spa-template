<?php

namespace App\Crawling;

use simple_html_dom;
use simple_html_dom_node;

class Crawler
{
    /**
     * @var mixed
     */
    protected $value;

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
        $this->value = $html;
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
            $this->value instanceof simple_html_dom_node
            || $this->value instanceof simple_html_dom
        ) {
            $this->value->find($selector, $index);
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
            $this->value instanceof simple_html_dom_node
            || $this->value instanceof simple_html_dom
        ) {
            $function = $caseSensitive ? 'strcasecmp' : 'strcmp';
            $found = [];

            foreach ($this->value->find($selector) as $element) {
                $result = call_user_func(
                    $function, $element->plaintext, $text
                );

                if ($result === 0) {
                    $found[] = $element;
                }
            }

            $this->value = $index === null
                ? $found
                : ($index >= 0 ? $found[$index] : $found[count($found) - 1]);
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
            $this->value instanceof simple_html_dom_node
            || $this->value instanceof simple_html_dom
        ) {
            $function = $caseSensitive ? 'stripos' : 'strpos';
            $found = [];

            foreach ($this->value->find($selector) as $element) {
                $result = call_user_func(
                    $function, $element->plaintext, $text
                );

                if ($result !== false) {
                    $found[] = $element;
                }
            }

            $this->value = $index === null
                ? $found
                : ($index >= 0 ? $found[$index] : $found[count($found) - 1]);
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
            $this->value instanceof simple_html_dom_node
            || $this->value instanceof simple_html_dom
        ) {
            $function = $caseSensitive ? 'stripos' : 'strpos';
            $found = [];

            foreach ($this->value->find($selector) as $element) {
                $result = call_user_func(
                    $function, $element->plaintext, $text
                );

                if ($result === 0) {
                    $found[] = $element;
                }
            }

            $this->value = $index === null
                ? $found
                : ($index >= 0 ? $found[$index] : $found[count($found) - 1]);
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
            $this->value instanceof simple_html_dom_node
            || $this->value instanceof simple_html_dom
        ) {
            $function = $caseSensitive ? 'stripos' : 'strpos';
            $found = [];

            foreach ($this->value->find($selector) as $element) {
                $result = call_user_func(
                    $function, $element->plaintext, $text
                );

                $pos = strlen($element->plaintext) - strlen($text);

                if ($result === $pos) {
                    $found[] = $element;
                }
            }

            $this->value = $index === null
                ? $found
                : ($index >= 0 ? $found[$index] : $found[count($found) - 1]);
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
            $this->value instanceof simple_html_dom_node
            || $this->value instanceof simple_html_dom
        ) {
            $found = [];

            foreach ($this->value->find($selector) as $element) {
                if (preg_match($this->normalizePattern($pattern), $element->plaintext)) {
                    $found[] = $element;
                }
            }

            $this->value = $index === null
                ? $found
                : ($index >= 0 ? $found[$index] : $found[count($found) - 1]);
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
     * @param  string  $name
     * @return self
     */
    public function getAttribute($name)
    {
        if (
            $this->value instanceof simple_html_dom_node
            || $this->value instanceof simple_html_dom
        ) {
            $this->value = $this->value->{$name};
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
        if (preg_match($this->normalizePattern($pattern), (string) $this->value, $matches)) {
            if (isset($matches[$capturingGroup])) {
                $this->value = $matches[$capturingGroup];
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
        if (preg_match_all($this->normalizePattern($pattern), (string) $this->value, $matches)) {
            $result = [];

            foreach ($matches as $match) {
                if (isset($match[$capturingGroup])) {
                    $result[] = $match[$capturingGroup];
                }
            }

            $this->value = $result;
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
        $this->value = str_replace(
            $search, $replace, (string) $this->value
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
        $this->value = preg_replace(
            $pattern, $replacement, (string) $this->value
        );

        return $this;
    }

    /**
     * @param  int|null  $count
     * @return self
     */
    public function takeDigits($count = null)
    {
        $this->value = substr(
            preg_replace('/\D/', '', $this->value), 0, $count
        );

        return $this;
    }

    /**
     * @return self
     */
    public function takeInteger()
    {
        if (preg_match('/(\d+)/', $this->value, $matches)) {
            if (isset($matches[1])) {
                $this->value = $matches[1];
            }
        }

        return $this;
    }

    /**
     * @return self
     */
    public function takeFloat()
    {
        if (preg_match('/(\d+[.,]\d+)/', $this->value, $matches)) {
            if (isset($matches[1])) {
                $this->value = $matches[1];
            }
        }

        return $this;
    }

    /**
     * @return self
     */
    public function takeNumeric()
    {
        if (preg_match('/(\d+(?:[.,]\d+)?)/', $this->value, $matches)) {
            if (isset($matches[1])) {
                $this->value = $matches[1];
            }
        }

        return $this;
    }

    /**
     * @return self
     */
    public function removeSpaces()
    {
        $this->value = preg_replace(
            '/\s/', '', $this->value
        );

        return $this;
    }

    /**
     * @return self
     */
    public function removeHtmlEntities()
    {
        $this->value = preg_replace(
            '/&[a-zA-Z]+;/', '', $this->value
        );

        return $this;
    }

    /**
     * @param  string  $value
     * @return self
     */
    public function append($value)
    {
        $this->value .= $value;
        return $this;
    }

    /**
     * @param  string  $value
     * @return self
     */
    public function prepend($value)
    {
        $this->value = $value.$this->value;
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
                $this->value = (bool) $this->value;
                break;
            case 'integer':
            case 'int':
                $this->value = (int) $this->value;
                break;
            case 'double':
            case 'float':
            case 'real':
                $this->value = (float) str_replace(',', '.', $this->value);
                break;
            case 'object':
                $this->value = (object) $this->value;
                break;
            case 'array':
                $this->value = is_array($this->value) ? $this->value : (array) $this->value;
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
        $this->value = strtotime($this->value);
        return $this;
    }

    /**
     * @param  string  $format
     * @return self
     */
    public function castToDateTime($format)
    {
        $this->value = date(
            $format, $this->castToTimestamp()
        );

        return $this;
    }

    /**
     * @return mixed
     */
    public function get()
    {
        return $this->value;
    }

    /**
     * @param  string  $pattern
     * @return string
     */
    protected function normalizePattern($pattern)
    {
        return preg_quote(
            '/'.trim($pattern, '/').'/'
        );
    }
}

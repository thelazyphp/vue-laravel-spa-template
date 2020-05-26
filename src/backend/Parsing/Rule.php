<?php

namespace App\Parsing;

class Rule
{
    /**
     * @var mixed
     */
    protected $defaultValue = null;

    /**
     * @var string
     */
    protected $prepend = '';

    /**
     * @var string
     */
    protected $append = '';

    /**
     * @var string
     */
    protected $castType = 'string';

    /**
     * @var array
     */
    protected $selectors = [];

    /**
     * @var array
     */
    protected $methods = [];

    /**
     * @var string
     */
    protected $property = 'plaintext';

    /**
     * @var array
     */
    protected $patterns = [];

    /**
     * @var array
     */
    protected $replacements = [];

    /**
     *
     */
    public function __construct()
    {
        //
    }

    /**
     * Getters:
     */

    /**
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * @return string
     */
    public function getPrepend()
    {
        return $this->prepend;
    }

    /**
     * @return string
     */
    public function getAppend()
    {
        return $this->append;
    }

    /**
     * @return string
     */
    public function getCastType()
    {
        return $this->castType;
    }

    /**
     * @return array
     */
    public function getSelectors()
    {
        return $this->selectors;
    }

    /**
     * @return array
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * @return string
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * @return array
     */
    public function getPatterns()
    {
        return $this->patterns;
    }

    /**
     * @return array
     */
    public function getReplacements()
    {
        return $this->replacements;
    }

    /**
     * Setters:
     */

    /**
     * @param  mixed  $value
     * @return self
     */
    public function defaultValue($value)
    {
        $this->defaultValue = $value;
        return $this;
    }

    /**
     * @param  string  $value
     * @return self
     */
    public function prepend($value)
    {
        $this->prepend = $value;
        return $this;
    }

    /**
     * @param  string  $value
     * @return self
     */
    public function append($value)
    {
        $this->append = $value;
        return $this;
    }

    /**
     * @param  string  $type
     * @return self
     */
    public function castTo($type)
    {
        $this->castType = $type;
        return $this;
    }

    /**
     * @return self
     */
    public function castToBoolean()
    {
        $this->castType = 'bool';
        return $this;
    }

    /**
     * @return self
     */
    public function castToFloat()
    {
        $this->castType = 'float';
        return $this;
    }

    /**
     * @return self
     */
    public function castToInteger()
    {
        $this->castType = 'int';
        return $this;
    }

    /**
     * @param  string  $selector
     * @param  int  $index
     * @param  callback  $callback
     * @return self
     */
    public function find(
        $selector,
        $index = 0,
        $callback = null
    ) {
        $this->selectors[] = [
            'selector' => $selector,
            'index' => $index,
            'callback' => $callback,
        ];

        return $this;
    }

    /**
     * @param  string  $selector
     * @param  callback  $callback
     * @return self
     */
    public function findAll($selector, $callback = null) {
        $this->selectors[] = [
            'selector' => $selector,
            'index' => null,
            'callback' => $callback,
        ];

        return $this;
    }

    /**
     * @param  string  $selector
     * @param  callback  $callback
     * @return self
     */
    public function findFirst($selector, $callback = null)
    {
        return $this->find(
            $selector, 0, $callback
        );
    }

    /**
     * @param  string  $selector
     * @param  callback  $callback
     * @return self
     */
    public function findLast($selector, $callback = null)
    {
        return $this->find(
            $selector, -1, $callback
        );
    }

    /**
     * @param  callback  $callback
     * @return self
     */
    public function parent($callback = null)
    {
        $this->methods[] = [
            'name' => 'parent',
            'params' => [],
            'callback' => $callback,
        ];

        return $this;
    }

    /**
     * @param  callback  $callback
     * @return self
     */
    public function children($callback = null)
    {
        $this->methods[] = [
            'name' => 'children',
            'params' => [],
            'callback' => $callback,
        ];

        return $this;
    }

    /**
     * @param  int  $index
     * @param  callback  $callback
     * @return self
     */
    public function child($index, $callback = null)
    {
        $this->methods[] = [
            'name' => 'children',
            'params' => [$index],
            'callback' => $callback,
        ];

        return $this;
    }

    /**
     * @param  callback  $callback
     * @return self
     */
    public function firstChild($callback = null)
    {
        $this->methods[] = [
            'name' => 'first_child',
            'params' => [],
            'callback' => $callback,
        ];

        return $this;
    }

    /**
     * @param  callback  $callback
     * @return self
     */
    public function lastChild($callback = null)
    {
        $this->methods[] = [
            'name' => 'last_child',
            'params' => [],
            'callback' => $callback,
        ];

        return $this;
    }

    /**
     * @param  callback  $callback
     * @return self
     */
    public function prevSibling($callback = null)
    {
        $this->methods[] = [
            'name' => 'prev_sibling',
            'params' => [],
            'callback' => $callback,
        ];

        return $this;
    }

    /**
     * @param  callback  $callback
     * @return self
     */
    public function nextSibling($callback = null)
    {
        $this->methods[] = [
            'name' => 'next_sibling',
            'params' => [],
            'callback' => $callback,
        ];

        return $this;
    }

    /**
     * @return self
     */
    public function innerText()
    {
        $this->property = 'plaintext';
        return $this;
    }

    /**
     * @return self
     */
    public function innerHtml()
    {
        $this->property = 'innertext';
        return $this;
    }

    /**
     * @return self
     */
    public function outerText()
    {
        $this->property = 'outertext';
        return $this;
    }

    /**
     * @return self
     */
    public function tagName()
    {
        $this->property = 'tag';
        return $this;
    }

    /**
     * @param  string  $name
     * @return self
     */
    public function attribute($name)
    {
        $this->property = $name;
        return $this;
    }

    /**
     * @param  string  $pattern
     * @param  int  $group
     * @param  callback  $callback
     * @return self
     */
    public function match(
        $pattern,
        $group = 0,
        $callback = null
    ) {
        $this->patterns[] = [
            'pattern' => $pattern,
            'group' => $group,
            'callback' => $callback,
        ];

        return $this;
    }

    /**
     * @param  string  $search
     * @param  string  $replace
     * @param  callback  $callback
     * @return self
     */
    public function replace(
        $search,
        $replace,
        $callback = null
    ) {
        $this->replacements[] = [
            'search' => $search,
            'replace' => $replace,
            'callback' => $callback,
        ];

        return $this;
    }

    /**
     * @param  string  $pattern
     * @param  string  $replacement
     * @param  callback  $callback
     * @return self
     */
    public function replaceMatched(
        $pattern,
        $replacement,
        $callback = null
    ) {
        $this->replacements[] = [
            'pattern' => $pattern,
            'replacement' => $replacement,
            'callback' => $callback,
        ];

        return $this;
    }
}

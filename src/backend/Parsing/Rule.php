<?php

namespace App\Parsing;

class Rule
{
    /**
     * @var mixed
     */
    protected $default = null;

    /**
     * @var array
     */
    protected $selectors = [];

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
     * @var string
     */
    protected $append = '';

    /**
     * @var string
     */
    protected $prepend = '';

    /**
     * @var string
     */
    protected $cast = 'string';

    /**
     * @param  mixed  $value
     * @return self
     */
    public function default($value)
    {
        $this->default = $value;
        return $this;
    }

    /**
     * @param  string  $selector
     * @param  int  $index
     * @return self
     */
    public function find($selector, $index = 0)
    {
        $this->selectors[] = [
            'selector' => $selector,
            'index' => $index,
        ];

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
     * @param  string  $pattern
     * @param  int  $index
     * @return self
     */
    public function match($pattern, $index = 0)
    {
        $this->patterns[] = [
            'pattern' => $pattern,
            'index' => $index,
        ];

        return $this;
    }

    /**
     * @param  string|string[]  $search
     * @param  string|string[]  $replacement
     * @return self
     */
    public function replace($search, $replacement)
    {
        $this->replacements[] = [
            'search' => $search,
            'replacement' => $replacement,
        ];

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
     * @param  string  $value
     * @return self
     */
    public function prepend($value)
    {
        $this->prepend = $value;
        return $this;
    }

    /**
     * @param  string  $type
     * @return self
     */
    public function castTo($type)
    {
        $this->cast = $type;
        return $this;
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
    public function castToInteger()
    {
        return $this->castTo('int');
    }

    /**
     * @return self
     */
    public function castToBoolean()
    {
        return $this->castTo('bool');
    }
}

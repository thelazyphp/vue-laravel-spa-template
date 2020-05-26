<?php

namespace App\Parsing;

class Rule
{
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
     * @param  string  $selector
     * @param  int  $index
     * @return self
     */
    public function find($selector, $index = 0)
    {
        $this->selectors[] = compact('selector', 'index');
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
    public function outerHtml()
    {
        $this->property = 'outertext';
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
    public function parent()
    {
        $this->methods[] = 'parent';
        return $this;
    }

    /**
     * @return self
     */
    public function prev()
    {
        $this->methods[] = 'prev_sibling';
        return $this;
    }

    /**
     * @return self
     */
    public function next()
    {
        $this->methods[] = 'next_sibling';
        return $this;
    }

    /**
     * @return self
     */
    public function firstChild()
    {
        $this->methods[] = 'first_child';
        return $this;
    }

    /**
     * @return self
     */
    public function lastChild()
    {
        $this->methods[] = 'last_child';
        return $this;
    }

    /**
     * @param  string  $pattern
     * @param  int  $index
     * @return self
     */
    public function match($pattern, $index = 0)
    {
        $this->patterns[] = compact('pattern', 'index');
        return $this;
    }
}

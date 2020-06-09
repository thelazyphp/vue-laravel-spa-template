<?php

namespace App\Parsing;

class Rule
{
    /**
     * @var array
     */
    protected $actions = [];

    /**
     *
     */
    public function __construct()
    {
        //
    }

    /**
     * @return array
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * @param string $selector
     * @return self
     */
    public function find($selector)
    {
        $this->actions[] = [
            'type' => 'find',
            'index' => 0,
            'selector' => $selector,
        ];

        return $this;
    }

    /**
     * @param string $selector
     * @return self
     */
    public function findAll($selector)
    {
        $this->actions[] = [
            'type' => 'find',
            'index' => null,
            'selector' => $selector,
        ];

        return $this;
    }

    /**
     * @param string $selector
     * @return self
     */
    public function findLast($selector)
    {
        $this->actions[] = [
            'type' => 'find',
            'index' => -1,
            'selector' => $selector,
        ];

        return $this;
    }
}

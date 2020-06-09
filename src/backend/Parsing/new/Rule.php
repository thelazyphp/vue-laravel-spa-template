<?php

namespace App\Parsing;

/**
 *
 */
class Rule
{
    /**
     * @var array
     */
    protected $methods = [];

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
    public function methods()
    {
        return $this->methods;
    }
}

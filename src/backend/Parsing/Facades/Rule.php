<?php

namespace App\Parsing\Facades;

class Rule
{
    /**
     * @param  string  $selector
     * @param  int  $index
     * @return \App\Parsing\Rule
     */
    public static function find($selector, $index = 0)
    {
        return (new \App\Parsing\Rule)->find($selector, $index);
    }

    /**
     * @param  string  $pattern
     * @param  int  $index
     * @return \App\Parsing\Rule
     */
    public static function match($pattern, $index = 0)
    {
        return (new \App\Parsing\Rule)->match($pattern, $index);
    }
}

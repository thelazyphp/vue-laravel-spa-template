<?php

namespace App\Parsing\Facades;

class Rule
{
    /**
     * @param  string  $model
     * @param  \App\Parsing\Rule[]  $rules
     * @return \App\Parsing\Relationship
     */
    public static function relationship($model, $rules = [])
    {
        //
    }

    /**
     * @param  mixed  $value
     * @return \App\Parsing\Rule
     */
    public static function defaultValue($value)
    {
        return (new \App\Parsing\Rule)->defaultValue($value);
    }

    /**
     * @param  string  $selector
     * @param  int  $index
     * @param  callback  $callback
     * @return \App\Parsing\Rule
     */
    public static function find(
        $selector,
        $index = 0,
        $callback = null
    ) {
        return (new \App\Parsing\Rule)->find($selector, $index, $callback);
    }

    /**
     * @param  string  $selector
     * @param  callback  $callback
     * @return \App\Parsing\Rule
     */
    public static function findAll($selector, $callback = null)
    {
        return (new \App\Parsing\Rule)->findAll($selector, $callback);
    }

    /**
     * @param  string  $selector
     * @param  callback  $callback
     * @return \App\Parsing\Rule
     */
    public static function findFirst($selector, $callback = null)
    {
        return (new \App\Parsing\Rule)->findFirst($selector, $callback);
    }

    /**
     * @param  string  $selector
     * @param  callback  $callback
     * @return \App\Parsing\Rule
     */
    public static function findLast($selector, $callback = null)
    {
        return (new \App\Parsing\Rule)->findLast($selector, $callback);
    }

    /**
     * @param  string  $pattern
     * @param  int  $group
     * @param  callback  $callback
     * @return \App\Parsing\Rule
     */
    public static function match(
        $pattern,
        $group = 0,
        $callback = null
    ) {
        return (new \App\Parsing\Rule)->match($pattern, $group, $callback);
    }
}

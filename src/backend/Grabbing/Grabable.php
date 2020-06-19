<?php

namespace App\Grabbing;

/**
 *
 */
trait Grabable
{
    /**
     * @param mixed $url
     * @param array $rules
     * @param array $relationships
     *
     * @return void
     */
    public static function grab(
        $url,
        $rules,
        $relationships = [])
    {
        $grabber = new Grabber;

        $grabber->grabModels(
            static::class,
            $url,
            $rules,
            $relationships
        );
    }
}

<?php

namespace App\Scraping;

use Closure;

/**
 *
 */
class Relationship
{
    /**
     * @var string
     */
    protected $model;

    /**
     * @var array
     */
    protected $rules = [];

    /**
     * @var string[]
     */
    protected $uniqueKeys = [];

    /**
     * @param string $model
     * @param \Closure $rules
     * @param string[] $uniqueKeys
     */
    public function __construct(
        $model,
        Closure $rules,
        $uniqueKeys = [])
    {
        $this->model = $model;

        $rules = $rules->bindTo(
            $this,
            $this
        );

        $rules();

        $this->uniqueKeys = $uniqueKeys;
    }

    /**
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * @return string[]
     */
    public function getUniqueKeys()
    {
        return $this->uniqueKeys;
    }
}

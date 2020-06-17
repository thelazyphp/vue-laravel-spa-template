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
     * @var string
     */
    protected $localKey = 'id';

    /**
     * @var string[]
     */
    protected $uniqueKeys = [];

    /**
     * @param string $model
     * @param \Closure $rules
     * @param string $localKey
     * @param string[] $uniqueKeys
     */
    public function __construct(
        $model,
        Closure $rules,
        $localKey = 'id',
        $uniqueKeys = [])
    {
        $this->model = $model;

        $rules = $rules->bindTo(
            $this,
            $this
        );

        $rules();

        $this->localKey = $localKey;
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
     * @return string
     */
    public function getLocalKey()
    {
        return $this->localKey;
    }

    /**
     * @return string[]
     */
    public function getUniqueKeys()
    {
        return $this->uniqueKeys;
    }
}

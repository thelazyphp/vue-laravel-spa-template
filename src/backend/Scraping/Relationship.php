<?php

namespace App\Scraping;

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
    protected $rules;

    /**
     * @param string $model
     * @param array $rules
     */
    public function __construct($model, $rules)
    {
        $this->model = $model;
        $this->rules = $rules;
    }
}

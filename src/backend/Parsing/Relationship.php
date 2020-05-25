<?php

namespace App\Parsing;

class Relationship
{
    /**
     * @var string
     */
    protected $model;

    /**
     * @var \App\Parsing\Rule[]
     */
    protected $rules = [];

    /**
     * @param  string  $model
     * @param  \App\Parsing\Rule[]  $rules
     */
    public function __construct($model, $rules)
    {
        $this->model = $model;
        $this->rules = $rules;
    }
}

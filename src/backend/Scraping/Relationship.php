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
     * @var callable
     */
    protected $beforeCallback;

    /**
     * @var callable
     */
    protected $afterCallback;

    /**
     * @param string $model
     * @param \Closure|array $rules
     * @param string $localKey
     * @param string[] $uniqueKeys
     */
    public function __construct(
        $model,
        $rules = [],
        $localKey = 'id',
        $uniqueKeys = [])
    {
        $this->model = $model;
        $this->rules($rules);
        $this->localKey($localKey);
        $this->uniqueKeys($uniqueKeys);
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

    /**
     * @return callable
     */
    public function getBeforeCallback()
    {
        return $this->beforeCallback;
    }

    /**
     * @return callable
     */
    public function getAfterCallback()
    {
        return $this->afterCallback;
    }

    /**
     * @param \Closure|array $rules
     * @return self
     */
    public function rules($rules)
    {
        if (is_array($rules)) {
            $this->rules = $rules;
        } else {
            $rules = $rules->bindTo(
                $this,
                $this
            );

            $rules();
        }

        return $this;
    }

    /**
     * @param string $key
     * @return self
     */
    public function localKey($key)
    {
        $this->localKey = $key;

        return $this;
    }

    /**
     * @param string[] $keys
     * @return self
     */
    public function uniqueKeys($keys)
    {
        $this->uniqueKeys = $keys;

        return $this;
    }

    /**
     * @param string $key
     * @return self
     */
    public function uniqueKey($key)
    {
        return $this->uniqueKeys([$key]);
    }

    /**
     * @param callable $callback
     * @return self
     */
    public function before(callable $callback)
    {
        $this->beforeCallback = $callback;

        return $this;
    }

    /**
     * @param callable $callback
     * @return self
     */
    public function after(callable $callback)
    {
        $this->afterCallback = $callback;

        return $this;
    }
}

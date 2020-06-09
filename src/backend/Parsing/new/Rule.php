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
    public function actions()
    {
        return $this->actions;
    }

    /**
     * @param string $selector
     * @param int|null $index
     *
     * @return self
     */
    public function find($selector, $index = 0)
    {
        $type = 'find';

        $this->actions[] = compact(
            'type', 'selector', 'index'
        );

        return $this;
    }

    /**
     * @param string $selector
     * @return self
     */
    public function findAll($selector)
    {
        return $this->find($selector, null);
    }

    /**
     * @param string $selector
     * @param mixed $value
     * @param int|null $index
     * @param bool $ignoreCase
     *
     * @return self
     */
    public function findWithText(
        $selector,
        $value,
        $index = 0,
        $ignoreCase = false
    ) {
        $type = 'findWithText';
        $cond = 'equals';

        $this->actions[] = compact(
            'type',
            'cond',
            'selector',
            'value',
            'inxdex',
            'ignoreCase'
        );

        return $this;
    }

    /**
     * @param string $selector
     * @param mixed $value
     * @param int|null $index
     * @param bool $ignoreCase
     *
     * @return self
     */
    public function findWithTextHas(
        $selector,
        $value,
        $index = 0,
        $ignoreCase = false
    ) {
        $type = 'findWithText';
        $cond = 'has';

        $this->actions[] = compact(
            'type',
            'cond',
            'selector',
            'value',
            'inxdex',
            'ignoreCase'
        );

        return $this;
    }

    /**
     * @param string $selector
     * @param mixed $value
     * @param int|null $index
     * @param bool $ignoreCase
     *
     * @return self
     */
    public function findWithTextStarts(
        $selector,
        $value,
        $index = 0,
        $ignoreCase = false
    ) {
        $type = 'findWithText';
        $cond = 'starts';

        $this->actions[] = compact(
            'type',
            'cond',
            'selector',
            'value',
            'inxdex',
            'ignoreCase'
        );

        return $this;
    }

    /**
     * @param string $selector
     * @param mixed $value
     * @param int|null $index
     * @param bool $ignoreCase
     *
     * @return self
     */
    public function findWithTextEnds(
        $selector,
        $value,
        $index = 0,
        $ignoreCase = false
    ) {
        $type = 'findWithText';
        $cond = 'ends';

        $this->actions[] = compact(
            'type',
            'cond',
            'selector',
            'value',
            'inxdex',
            'ignoreCase'
        );

        return $this;
    }
}

<?php

namespace App\Parsing;

use Closure;
use Illuminate\Support\Collection;

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
            'type',
            'selector',
            'index',
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
     * @param string $value
     * @param string $selector
     * @param int|null $index
     * @param bool $ignoreCase
     *
     * @return self
     */
    public function findWithText(
        $value,
        $selector = null,
        $index = 0,
        $ignoreCase = false)
    {
        $type = 'findWithText';

        $this->actions[] = compact(
            'type',
            'value',
            'selector',
            'index',
            'ignoreCase',
        );

        return $this;
    }

    /**
     * @param string $value
     * @param string $selector
     * @param bool $ignoreCase
     *
     * @return self
     */
    public function findAllWithText(
        $value,
        $selector = null,
        $ignoreCase = false)
    {
        return $this->findWithText(
            $value, $selector, null, $ignoreCase
        );
    }

    /**
     * @param string $value
     * @param string $selector
     * @param int|null $index
     * @param bool $ignoreCase
     *
     * @return self
     */
    public function findWithTextHas(
        $value,
        $selector = null,
        $index = 0,
        $ignoreCase = false)
    {
        $type = 'findWithTextHas';

        $this->actions[] = compact(
            'type',
            'value',
            'selector',
            'index',
            'ignoreCase',
        );

        return $this;
    }

    /**
     * @param string $value
     * @param string $selector
     * @param bool $ignoreCase
     *
     * @return self
     */
    public function findAllWithTextHas(
        $value,
        $selector = null,
        $ignoreCase = false)
    {
        return $this->findWithTextHas(
            $value, $selector, null, $ignoreCase
        );
    }

    /**
     * @param string $value
     * @param string $selector
     * @param int|null $index
     * @param bool $ignoreCase
     *
     * @return self
     */
    public function findWithTextStarts(
        $value,
        $selector = null,
        $index = 0,
        $ignoreCase = false)
    {
        $type = 'findWithTextStarts';

        $this->actions[] = compact(
            'type',
            'value',
            'selector',
            'index',
            'ignoreCase',
        );

        return $this;
    }

    /**
     * @param string $value
     * @param string $selector
     * @param bool $ignoreCase
     *
     * @return self
     */
    public function findAllWithTextStarts(
        $value,
        $selector = null,
        $ignoreCase = false)
    {
        return $this->findWithTextStarts(
            $value, $selector, null, $ignoreCase
        );
    }

    /**
     * @param string $value
     * @param string $selector
     * @param int|null $index
     * @param bool $ignoreCase
     *
     * @return self
     */
    public function findWithTextEnds(
        $value,
        $selector = null,
        $index = 0,
        $ignoreCase = false)
    {
        $type = 'findWithTextEnds';

        $this->actions[] = compact(
            'type',
            'value',
            'selector',
            'index',
            'ignoreCase',
        );

        return $this;
    }

    /**
     * @param string $value
     * @param string $selector
     * @param bool $ignoreCase
     *
     * @return self
     */
    public function findAllWithTextEnds(
        $value,
        $selector = null,
        $ignoreCase = false)
    {
        return $this->findWithTextEnds(
            $value, $selector, null, $ignoreCase
        );
    }

    /**
     * @param string $pattern
     * @param string $selector
     * @param int|null $index
     *
     * @return self
     */
    public function findWithTextMatches(
        $pattern,
        $selector = null,
        $index = 0)
    {
        $type = 'findWithTextMatches';

        $this->actions[] = compact(
            'type',
            'pattern',
            'selector',
            'index',
        );

        return $this;
    }

    /**
     * @param string $pattern
     * @param string $selector
     *
     * @return self
     */
    public function findAllWithTextMatches($pattern, $selector = null)
    {
        return $this->findWithTextMatches($pattern, $selector, null);
    }
}

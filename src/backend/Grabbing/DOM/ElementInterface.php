<?php

namespace App\Grabbing\DOM;

/**
 * @author Dzianis Chaika <dzianischaika@outlook.com>
 */
interface ElementInterface extends NodeInterface
{
    /**
     * @param string $name
     * @return bool
     */
    public function hasAttribute($name);

    /**
     * @param string $name
     * @return string
     */
    public function getAttribute($name);

    /**
     * @return string
     */
    public function innerHTML();

    /**
     * @return string
     */
    public function outerHTML();

    /**
     * @return string
     */
    public function innerText();

    /**
     * @return string
     */
    public function id();

    /**
     * @return string
     */
    public function name();

    /**
     * @return string
     */
    public function tagName();

    /**
     * @return string
     */
    public function className();

    /**
     * @return string[]
     */
    public function classList();

    /**
     * @param string $selector
     * @return ElementInterface|null
     */
    public function querySelector($selector);

    /**
     * @param string $selector
     * @return ElementInterface[]
     */
    public function querySelectorAll($selector);

    /**
     * @param string $name
     * @return ElementInterface[]
     */
    public function getElementsByTagName($name);

    /**
     * @param string $name
     * @return ElementInterface[]
     */
    public function getElementsByClassName($name);
}

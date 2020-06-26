<?php

namespace App\Grabbing\DOM;

/**
 *
 */
interface ElementInterface
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
     * @return ElementInterface|null
     */
    public function parentElement();

    /**
     * @return ElementInterface[]
     */
    public function childElements();

    /**
     * @return ElementInterface|null
     */
    public function firstChildElement();

    /**
     * @return ElementInterface|null
     */
    public function lastChildElement();

    /**
     * @return ElementInterface|null
     */
    public function previousSiblingElement();

    /**
     * @return ElementInterface|null
     */
    public function nextSiblingElement();

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
}

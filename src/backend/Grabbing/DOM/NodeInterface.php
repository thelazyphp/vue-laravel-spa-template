<?php

namespace App\Grabbing\DOM;

/**
 * @author Dzianis Chaika <dzianischaika@outlook.com>
 */
interface NodeInterface
{
    /**
     * @return DocumentInterface|null
     */
    public function ownerDocument();

    /**
     * @return bool
     */
    public function hasAttributes();

    /**
     * @return array
     */
    public function attributes();

    /**
     * @return ElementInterface|null
     */
    public function parentElement();

    /**
     * @return bool
     */
    public function hasChildElements();

    /**
     * @return ElementInterface[]
     */
    public function childElements();

    /**
     * @return ElementInterface|null
     */
    public function firstElementChild();

    /**
     * @return ElementInterface|null
     */
    public function lastElementChild();

    /**
     * @return ElementInterface|null
     */
    public function previousElementSibling();

    /**
     * @return ElementInterface|null
     */
    public function nextElementSibling();
}

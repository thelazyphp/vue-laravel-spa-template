<?php

namespace App\Grabbing\DOM;

use DOMNode;
use DOMXPath;

/**
 * @author Dzianis Chaika <dzianischaika@outlook.com>
 */
abstract class Node implements NodeInterface
{
    /**
     * @var DOMNode
     */
    protected $node;

    /**
     * @var DOMXPath
     */
    protected $path;

    /**
     * @return DocumentInterface|null
     */
    public function ownerDocument()
    {
        return is_null($element = $this->node->ownerDocument)
            ? null
            : new Document($element);
    }

    /**
     * @return bool
     */
    public function hasAttributes()
    {
        return $this->node->hasAttributes();
    }

    /**
     * @return array
     */
    public function attributes()
    {
        $attributes = [];

        if ($this->hasAttributes()) {
            foreach ($this->node->attributes as $node) {
                $attributes[$node->nodeName] = $node->nodeValue;
            }
        }

        return $attributes;
    }

    /**
     * @return ElementInterface|null
     */
    public function parentElement()
    {
        $element = $this->node->parentNode;

        for (;; $element = $element->parentNode) {
            if (is_null($element) || $element->nodeType == XML_ELEMENT_NODE) {
                break;
            }
        }

        return new Element(
            $element, $this->path
        );
    }

    /**
     * @return bool
     */
    public function hasChildElements()
    {
        return count($this->childElements()) > 0;
    }

    /**
     * @return ElementInterface[]
     */
    public function childElements()
    {
        $elements = [];

        foreach ($this->node->childNodes as $node) {
            if ($node->nodeType == XML_ELEMENT_NODE) {
                $elements[] = new Element($node, $this->path);
            }
        }

        return $elements;
    }

    /**
     * @return ElementInterface|null
     */
    public function firstElementChild()
    {
        $elements = $this->childElements();

        return count($elements) == 0
            ? null
            : $elements[0];
    }

    /**
     * @return ElementInterface|null
     */
    public function lastElementChild()
    {
        $elements = $this->childElements();

        return ($count = count($elements)) == 0
            ? null
            : $elements[$count - 1];
    }

    /**
     * @return ElementInterface|null
     */
    public function previousElementSibling()
    {
        $element = $this->node->previousSibling;

        for (;; $element = $element->previousSibling) {
            if (is_null($element) || $element->nodeType == XML_ELEMENT_NODE) {
                break;
            }
        }

        return new Element(
            $element, $this->path
        );
    }

    /**
     * @return ElementInterface|null
     */
    public function nextElementSibling()
    {
        $element = $this->node->nextSibling;

        for (;; $element = $element->nextSibling) {
            if (is_null($element) || $element->nodeType == XML_ELEMENT_NODE) {
                break;
            }
        }

        return new Element(
            $element, $this->path
        );
    }
}

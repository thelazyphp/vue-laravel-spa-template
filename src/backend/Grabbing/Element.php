<?php

namespace App\Grabbing;

use DOMElement;
use DOMXPath;

/**
 *
 */
class Element
{
    /**
     * @var \DOMElement
     */
    protected $node;

    /**
     * @var \DOMXPath
     */
    protected $xpath;

    /**
     * @var array
     */
    protected $attrs = [];

    /**
     * @param \DOMElement $node
     * @param \DOMXPath $xpath
     */
    public function __construct(DOMElement $node, DOMXPath $xpath)
    {
        $this->node = $node;
        $this->xpath = $xpath;

        if ($this->node->hasAttributes()) {
            foreach ($this->node->attributes as $node) {
                $this->attrs[$node->nodeName] = $node->nodeValue;
            }
        }
    }

    /**
     * @return \DOMElement
     */
    public function getNode()
    {
        return $this->node;
    }

    /**
     * @return \DOMXPath
     */
    public function getXpath()
    {
        return $this->xpath;
    }

    /**
     * @param string $xpath
     * @param int|null $index
     *
     * @return \App\Grabbing\Element[]|\App\Grabbing\Element|null
     */
    public function find($xpath, $index = null)
    {
        $nodes = $this->xpath->query($xpath, $this->node);

        if ($nodes === false) {
            return is_null($index) ? [] : null;
        }

        $elements = [];

        foreach ($nodes as $node) {
            if ($node->nodeType == XML_ELEMENT_NODE) {
                $elements[] = new static($node, $this->xpath);
            }
        }

        if (is_null($index)) {
            return $elements;
        }

        if ($index < 0) {
            $index += count($elements);
        }

        return isset($elements[$index]) ? $elements[$index] : null;
    }

    /**
     * @return \App\Grabbing\Element|null
     */
    public function previousSiblingElement()
    {
        return is_null($this->node->previousSibling)
            || $this->node->previousSibling->nodeType != XML_ELEMENT_NODE
                ? null
                : new static($this->node->previousSibling, $this->xpath);
    }

    /**
     * @return \App\Grabbing\Element|null
     */
    public function nextSiblingElement()
    {
        return is_null($this->node->nextSibling)
            || $this->node->nextSibling->nodeType != XML_ELEMENT_NODE
                ? null
                : new static($this->node->nextSibling, $this->xpath);
    }

    /**
     * @return \App\Grabbing\Element|null
     */
    public function parentElement()
    {
        return is_null($this->node->parentNode)
            || $this->node->parentNode->nodeType != XML_ELEMENT_NODE
                ? null
                : new static($this->node->parentNode, $this->xpath);
    }

    /**
     * @return \App\Grabbing\Element[]
     */
    public function childElements()
    {
        $elements = [];

        foreach ($this->node->childNodes as $node) {
            if ($node->nodeType == XML_ELEMENT_NODE) {
                $elements[] = new static($node, $this->xpath);
            }
        }

        return $elements;
    }

    /**
     * @param int $index
     * @return \App\Grabbing\Element|null
     */
    public function childElement($index)
    {
        $elements = $this->childElements();

        if ($index < 0) {
            $index += count($elements);
        }

        return isset($elements[$index]) ? $elements[$index] : null;
    }

    /**
     * @return \App\Grabbing\Element|null
     */
    public function firstChildElement()
    {
        return $this->childElement(0);
    }

    /**
     * @return \App\Grabbing\Element|null
     */
    public function lastChildElement()
    {
        return $this->childElement(-1);
    }

    /**
     * @return string
     */
    public function innerHtml()
    {
        if (is_null($this->node->ownerDocument)) {
            return '';
        }

        return $this->node->ownerDocument->saveHTML($this->node);
    }

    /**
     * @return string
     */
    public function outerHtml()
    {
        if (is_null($this->node->ownerDocument)) {
            return '';
        }

        return $this->node->ownerDocument->saveHTML();
    }

    /**
     * @return string
     */
    public function innerText()
    {
        return $this->node->textContent;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function __isset($name)
    {
        return $this->hasAttribute($name);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasAttribute($name)
    {
        return isset($this->attrs[$name]);
    }

    /**
     * @return array
     */
    public function getAllAttributes()
    {
        return $this->attrs;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        switch ($name) {
            case 'innerHtml':
                return $this->innerHtml();
            case 'outerHtml':
                return $this->outerHtml();
            case 'innerText':
                return $this->innerText();
        }

        return $this->getAttribute($name);
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function getAttribute($name)
    {
        return $this->hasAttribute($name) ? $this->attrs[$name] : null;
    }
}

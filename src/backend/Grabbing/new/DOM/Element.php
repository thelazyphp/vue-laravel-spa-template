<?php

namespace App\Grabbing\DOM;

use DOMXPath;
use DOMElement;
use Symfony\Component\CssSelector\CssSelectorConverter;

/**
 * @property array $attributes
 * @property string $innerHTML
 * @property string $outerHTML
 * @property string $innerText
 */
class Element implements ElementInterface
{
    /**
     * @var DOMXPath
     */
    protected $path;

    /**
     * @var DOMElement
     */
    protected $node;

    /**
     * @param DOMXPath $path
     * @param DOMElement $node
     */
    public function __construct(DOMXPath $path, DOMElement $node)
    {
        $this->path = $path;
        $this->node = $node;
    }

    /**
     * @return DOMXPath
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return DOMElement
     */
    public function getNode()
    {
        return $this->node;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasAttribute($name)
    {
        return $this->node->hasAttribute($name);
    }

    /**
     * @param string $name
     * @return string
     */
    public function getAttribute($name)
    {
        return $this->node->getAttribute($name);
    }

    /**
     * @return string
     */
    public function innerHTML()
    {
        return is_null($this->node->ownerDocument)
            ? ''
            : $this->node->ownerDocument->saveHTML($this->node);
    }

    /**
     * @return string
     */
    public function outerHTML()
    {
        return is_null($this->node->ownerDocument)
            ? ''
            : $this->node->ownerDocument->saveHTML();
    }

    /**
     * @return string
     */
    public function innerText()
    {
        return $this->node->textContent;
    }

    /**
     * @return ElementInterface|null
     */
    public function parentElement()
    {
        if (
            is_null($this->node->parentNode)
            || $this->node->parentNode->nodeType != XML_ELEMENT_NODE
        ) {
            return null;
        }

        return new Element(
            $this->path, $this->node->parentNode
        );
    }

    /**
     * @return ElementInterface[]
     */
    public function childElements()
    {
        $elements = [];

        foreach ($this->node->childNodes as $node) {
            if ($node->nodeType == XML_ELEMENT_NODE) {
                $elements[] = new Element($this->path, $node);
            }
        }

        return $elements;
    }

    /**
     * @return ElementInterface|null
     */
    public function firstChildElement()
    {
        $elements = $this->childElements();

        if (empty($elements)) {
            return null;
        }

        return $elements[0];
    }

    /**
     * @return ElementInterface|null
     */
    public function lastChildElement()
    {
        $elements = $this->childElements();

        if (empty($elements)) {
            return null;
        }

        return $elements[count($elements) - 1];
    }

    /**
     * @return ElementInterface|null
     */
    public function previousSiblingElement()
    {
        if (
            is_null($this->node->previousSibling)
            || $this->node->previousSibling->nodeType != XML_ELEMENT_NODE
        ) {
            return null;
        }

        return new Element(
            $this->path, $this->node->previousSibling
        );
    }

    /**
     * @return ElementInterface|null
     */
    public function nextSiblingElement()
    {
        if (
            is_null($this->node->nextSibling)
            || $this->node->nextSibling->nodeType != XML_ELEMENT_NODE
        ) {
            return null;
        }

        return new Element(
            $this->path, $this->node->nextSibling
        );
    }

    /**
     * @param string $selector
     * @return ElementInterface|null
     */
    public function querySelector($selector)
    {
        $elements = $this->querySelectorAll($selector);

        if (empty($elements)) {
            return null;
        }

        return $elements[0];
    }

    /**
     * @param string $selector
     * @return ElementInterface[]
     */
    public function querySelectorAll($selector)
    {
        $expression = (new CssSelectorConverter())->toXPath($selector);
        $nodes = $this->path->query($expression, $this->node);

        if ($nodes === false) {
            return [];
        }

        $elements = [];

        foreach ($nodes as $node) {
            if ($node->nodeType == XML_ELEMENT_NODE) {
                $elements[] = new Element($this->path, $node);
            }
        }

        return $elements;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->innerHTML();
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
     * @return mixed
     */
    public function __get($name)
    {
        switch ($name) {
            case 'attributes':
                $attributes = [];

                if ($this->node->hasAttributes()) {
                    foreach ($this->node->attributes as $node) {
                        $attributes[$node->nodeName] = $node->nodeValue;
                    }
                }

                return $attributes;
            case 'innerHTML':
                return $this->innerHTML();
            case 'outerHTML':
                return $this->outerHTML();
            case 'innerText':
                return $this->innerText();
        }

        return $this->getAttribute($name);
    }
}

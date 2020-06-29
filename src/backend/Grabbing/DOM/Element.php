<?php

namespace App\Grabbing\DOM;

use DOMElement;
use DOMXPath;
use Symfony\Component\CssSelector\CssSelectorConverter;

/**
 * @author Dzianis Chaika <dzianischaika@outlook.com>
 */
class Element extends Node implements ElementInterface
{
    /**
     * @var DOMElement
     */
    protected $node;

    /**
     * @param DOMElement $node
     * @param DOMXPath $path
     */
    public function __construct(DOMElement $node, DOMXPath $path)
    {
        $this->node = $node;
        $this->path = $path;
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
        return '';
    }

    /**
     * @return string
     */
    public function outerHTML()
    {
        return '';
    }

    /**
     * @return string
     */
    public function innerText()
    {
        return '';
    }

    /**
     * @return string
     */
    public function id()
    {
        return $this->getAttribute('id');
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->getAttribute('name');
    }

    /**
     * @return string
     */
    public function tagName()
    {
        return $this->node->tagName;
    }

    /**
     * @return string
     */
    public function className()
    {
        return $this->getAttribute('class');
    }

    /**
     * @return string[]
     */
    public function classList()
    {
        return array_map(
            'trim', explode(' ', $this->className())
        );
    }

    /**
     * @param string $selector
     * @return ElementInterface|null
     */
    public function querySelector($selector)
    {
        return count($elements = $this->querySelectorAll($selector)) == 0
            ? null
            : $elements[0];
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
                $elements[] = new Element($node, $this->path);
            }
        }

        return $elements;
    }

    /**
     * @param string $name
     * @return ElementInterface[]
     */
    public function getElementsByTagName($name)
    {
        $elements = [];

        foreach ($this->node->getElementsByTagName($name) as $node) {
            $elements[] = new Element($node, $this->path);
        }

        return $elements;
    }

    /**
     * @param string $name
     * @return ElementInterface[]
     */
    public function getElementsByClassName($name)
    {
        return $this->querySelectorAll('[class="'.$name.'"]');
    }
}

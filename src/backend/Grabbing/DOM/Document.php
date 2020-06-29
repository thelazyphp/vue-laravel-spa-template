<?php

namespace App\Grabbing\DOM;

use DOMDocument;
use Psr\Http\Message\UriInterface;
use DOMXPath;
use Symfony\Component\CssSelector\CssSelectorConverter;

/**
 * @author Dzianis Chaika <dzianischaika@outlook.com>
 */
class Document extends Node implements DocumentInterface
{
    /**
     * @var DOMDocument
     */
    protected $node;

    /**
     * @param DOMDocument|null $node
     * @param UriInterface|string|null $documentURI
     */
    public function __construct(?DOMDocument $node = null, $documentURI = null)
    {
        if ($node instanceof DOMDocument) {
            $this->node = $node;
        } else {
            $this->node = new DOMDocument('1.0', 'utf-8');
            $this->node->formatOutput = true;
            $this->node->preserveWhiteSpace = false;
        }
    }

    /**
     * @return ElementInterface|null
     */
    public function documentElement()
    {
        return new Element(
            $this->node->documentElement, new DOMXPath($this->node)
        );
    }

    /**
     * @return UriInterface|null
     */
    public function documentURI()
    {
        //
    }

    /**
     * @param string $html
     * @return bool
     */
    public function loadHTML($html)
    {
        libxml_use_internal_errors(true);
        $result = $this->node->loadHTML($html);
        libxml_clear_errors();
        $this->node->normalizeDocument();

        return $result;
    }

    /**
     * @param string $filename
     * @return bool
     */
    public function loadHTMLFile($filename)
    {
        libxml_use_internal_errors(true);
        $result = $this->node->loadHTMLFile($filename);
        libxml_clear_errors();
        $this->node->normalizeDocument();

        return $result;
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
        $path = new DOMXPath($this->node);
        $nodes = $path->query($expression);

        if ($nodes === false) {
            return [];
        }

        $elements = [];

        foreach ($nodes as $node) {
            if ($node->nodeType == XML_ELEMENT_NODE) {
                $elements[] = new Element($node, $path);
            }
        }

        return $elements;
    }

    /**
     * @param string $id
     * @return ElementInterface|null
     */
    public function getElementById($id)
    {
        return is_null($element = $this->node->getElementById($id))
            ? null
            : $element;
    }

    /**
     * @param string $name
     * @return ElementInterface[]
     */
    public function getElementsByName($name)
    {
        return $this->querySelectorAll('[name="'.$name.'"]');
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

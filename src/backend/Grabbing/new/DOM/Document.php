<?php

namespace App\Grabbing\DOM;

use DOMDocument;
use DOMXPath;

/**
 * @property array $attributes
 * @property string $innerHTML
 * @property string $outerHTML
 * @property string $innerText
 */
class Document extends Element implements DocumentInterface
{
    /**
     * @var DOMDocument
     */
    protected $node;

    /**
     * @param DOMDocument|null $node
     */
    public function __construct(DOMDocument $node = null)
    {
        if ($node instanceof DOMDocument) {
            $this->node = $node;
        } else {
            $this->node = new DOMDocument;
            $this->node->formatOutput = true;
            $this->node->preserveWhiteSpace = false;
        }

        $this->path = new DOMXPath($this->node);
    }

    /**
     * @return DOMDocument
     */
    public function getNode()
    {
        return $this->node;
    }

    /**
     * @return string
     */
    public function innerHTML()
    {
        return $this->node->saveHTML();
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
        $this->path = new DOMXPath($this->node);

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
        $this->path = new DOMXPath($this->node);

        return $result;
    }
}

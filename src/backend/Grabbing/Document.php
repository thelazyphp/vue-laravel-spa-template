<?php

namespace App\Grabbing;

use DOMXPath;
use DOMNode;
use DOMDocument;

/**
 *
 */
class DomElement
{
    /**
     * @var \DOMNode
     */
    protected $node;

    /**
     * @var \DOMDocument
     */
    protected $document;

    /**
     * @param \DOMNode $node
     * @param \DOMDocument $document
     */
    public function __construct(DOMNode $node, DOMDocument $document)
    {
        $this->node = $node;
        $this->document = $document;
    }

    /**
     * @return \DOMNode
     */
    public function getNode()
    {
        return $this->node;
    }

    /**
     * @return \DOMDocument
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * @param string $selector
     * @param int|null $index
     *
     * @return \App\Grabbing\DomElement[]|\App\Grabbing\DomElement|null
     */
    public function find($selector, $index = 0)
    {
        $xPath = new DOMXPath($this->document);
        $found = [];

        foreach ($xPath->query($this->generateXPath($selector), $this->node) as $node) {
            $found[] = new static($node, $this->document);
        }

        if (is_null($index)) {
            return $found;
        }

        if ($index < 0) {
            $index += count($found);
        }

        return isset($found[$index]) ? $found[$index] : null;
    }

    /**
     * @return \App\Grabbing\DomElement
     */
    public function parent()
    {
        return new static(
            $this->node->parentNode, $this->document
        );
    }

    /**
     * @return \App\Grabbing\DomElement[]
     */
    public function children()
    {
        $nodes = [];

        foreach ($this->node->childNodes as $node) {
            if ($node instanceof DOMNode) {
                $nodes[] = new static(
                    $node, $this->document
                );
            }
        }

        return $nodes;
    }

    /**
     * @param int $index
     * @return \App\Grabbing\DomElement|null
     */
    public function child($index)
    {
        $children = $this->children();

        if ($index < 0) {
            $index += count($children);
        }

        return isset($children[$index]) ? $children[$index] : null;
    }

    /**
     * @param string $selector
     * @return string
     */
    protected function generateXPath($selector)
    {
        $replacements = [
            '/\s*>\s*/'                                  => '/',
            '/\s+/'                                      => '//',
            '/([\w-]+)#([\w-]+)/'                        => '$1[@id="$2"]',
            '/#([\w-]+)/'                                => '*[@id="$1"]',
            '/([\w-]+)\.([\w-]+)/'                       => '$1[contains(concat(" ", normalize-space(@class), " "), " $2 ")]',
            '/\.([\w-]+)/'                               => '*[contains(concat(" ", normalize-space(@class), " "), " $1 ")]',
            '/([\w-]+)\[([\w-]+)\]/'                     => '$1[@$2]',
            '/\[([\w-]+)\]/'                             => '*[@$1]',
            '/([\w-]+)\[([\w-]+)=[\'"]?(.*?)[\'"]?\]/'   => '$1[@$2="$3"]',
            '/\[([\w-]+)=[\'"]?(.*?)[\'"]?\]/'           => '*[@$1="$2"]',
            '/([\w-]+)\[([\w-]+)~=[\'"]?(.*?)[\'"]?\]/'  => '$1[contains(concat(" ", normalize-space(@$2), " "), " $3 ")]',
            '/\[([\w-]+)~=[\'"]?(.*?)[\'"]?\]/'          => '*[contains(concat(" ", normalize-space(@$1), " "), " $2 ")]',
            '/([\w-]+)\[([\w-]+)\^=[\'"]?(.*?)[\'"]?\]/' => '$1[starts-with(@$2, "$3")]',
            '/\[([\w-]+)\^=[\'"]?(.*?)[\'"]?\]/'         => '*[starts-with(@$1, "$2")]',
            '/([\w-]+)\[([\w-]+)\$=[\'"]?(.*?)[\'"]?\]/' => '$1[substring(@$2, string-length(@$2) - string-length("$3") + 1)="$3"]',
            '/\[([\w-]+)\$=[\'"]?(.*?)[\'"]?\]/'         => '*[substring(@$1, string-length(@$1) - string-length("$2") + 1)="$2"]',
            '/([\w-]+)\[([\w-]+)\*=[\'"]?(.*?)[\'"]?\]/' => '$1[contains(@$2, "$3")]',
            '/\[([\w-]+)\*=[\'"]?(.*?)[\'"]?\]/'         => '*[contains(@$1, "$2")]',
            '/\]\*\[/'                                   => '][',
            '/:nth-of-type\((\d+)\)/'                    => '[$1]',
            '/:contains\([\'"]?(.*?)[\'"]?\)/'           => '[contains(text(), "$1")]',
        ];

        return '//'.preg_replace(
            array_keys($replacements), array_values($replacements), trim($selector)
        );
    }
}

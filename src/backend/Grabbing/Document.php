<?php

namespace App\Grabbing;

use DOMDocument;
use DOMXPath;

/**
 *
 */
class Document extends Element
{
    /**
     * @var \DOMDocument
     */
    protected $node;

    /**
     * @param \DOMDocument|string|null $html
     */
    public function __construct($html = null)
    {
        if ($html instanceof DOMDocument) {
            $this->node = $html;
        } else {
            $this->node = new DOMDocument('1.0', 'utf-8');
            $this->node->formatOutput = true;
            $this->node->preserveWhiteSpace = false;

            if (is_string($html)) {
                $this->load($html);
            }
        }

        $this->xpath = new DOMXPath($this->node);
    }

    /**
     * @return string
     */
    public function innerHtml()
    {
        return $this->node->saveHTML();
    }

    /**
     * @return string
     */
    public function outerHtml()
    {
        return $this->innerHtml();
    }

    /**
     * @param string $html
     * @return void
     */
    public function load($html)
    {
        libxml_use_internal_errors(true);
        $this->node->loadHTML($html);
        libxml_clear_errors();
        $this->node->normalizeDocument();
    }

    /**
     * @param string $file
     * @return void
     */
    public function loadFile($file)
    {
        libxml_use_internal_errors(true);
        $this->node->loadHTMLFile($file);
        libxml_clear_errors();
        $this->node->normalizeDocument();
    }

    /**
     * @param string $url
     * @param array $opts
     *
     * @return void
     */
    public function loadUrl($url, $opts = [])
    {
        $opts[CURLOPT_RETURNTRANSFER] = true;
        $opts[CURLOPT_URL] = $url;

        if (!isset($opts[CURLOPT_USERAGENT])) {
            $opts[CURLOPT_USERAGENT] = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36';
        }

        $ch = curl_init();
        curl_setopt_array($ch, $opts);
        $html = curl_exec($ch);
        curl_close($ch);

        if ($html !== false) {
            $this->load($html);
        }
    }
}

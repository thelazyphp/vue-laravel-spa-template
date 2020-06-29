<?php

namespace App\Grabbing\DOM;

use Psr\Http\Message\UriInterface;

/**
 * @author Dzianis Chaika <dzianischaika@outlook.com>
 */
interface DocumentInterface extends NodeInterface
{
    /**
     * @return ElementInterface|null
     */
    public function documentElement();

    /**
     * @return UriInterface|null
     */
    public function documentURI();

    /**
     * @param string $html
     * @return bool
     */
    public function loadHTML($html);

    /**
     * @param string $filename
     * @return bool
     */
    public function loadHTMLFile($filename);

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

    /**
     * @param string $id
     * @return ElementInterface|null
     */
    public function getElementById($id);

    /**
     * @param string $name
     * @return ElementInterface[]
     */
    public function getElementsByName($name);

    /**
     * @param string $name
     * @return ElementInterface[]
     */
    public function getElementsByTagName($name);

    /**
     * @param string $name
     * @return ElementInterface[]
     */
    public function getElementsByClassName($name);
}

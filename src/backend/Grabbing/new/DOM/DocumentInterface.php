<?php

namespace App\Grabbing\DOM;

/**
 *
 */
interface DocumentInterface extends ElementInterface
{
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
}

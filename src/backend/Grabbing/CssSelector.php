<?php

namespace App\Grabbing;

/**
 *
 */
class CssSelector
{
    /**
     * @var array
     */
    private static $cache = [];

    /**
     * @param string $selector
     * @return string
     */
    public static function cssSelectorToXPath($selector)
    {
        $selector = trim($selector);

        if (isset(static::$cache[$selector])) {
            return static::$cache[$selector];
        }

        $replacements = [
            // [attr]
            // [attr=value]
            // E[attr]
            // E[attr=value]
            '/([\w-]*)\[(.*?)\]/' => function ($matches) {
                $elem = $matches[1] ? $matches[1] : '*';
                $attr = $matches[2];
                $result = '[@'.$attr.']';

                if (preg_match('/^([\w-]+)([~|^$*]?=)[\'"]?(.*?)[\'"]?$/', $attr, $matches)) {
                    $attr = $matches[1];
                    $op = $matches[2];
                    $value = $matches[3];

                    switch ($op) {
                        case '=':
                            $result = '[@'.$attr.'="'.$value.'"]';
                            break;
                        case '*=':
                            $result = '[contains(@'.$attr.', "'.$value.'")]';
                            break;
                        case '$=':
                            $result = '[ends-with(@'.$attr.', "'.$value.'")]';
                            break;
                        case '^=':
                            $result = '[starts-with(@'.$attr.', "'.$value.'")]';
                            break;
                        case '~=':
                            $result = '[contains(concat(" ", normalize-space(@'.$attr.'), " "), " ' . $value . ' ")]';
                            break;
                        case '|=':
                            $result = '[@'.$attr.'="'.$value.'" or starts-with(@'.$attr.', concat("'.$value.'", "-"))]';
                            break;
                    }
                }

                return $elem.$result;
            },

            // #id
            // #id1#id2
            // E#id
            // E#id1#id2
            '/([\w-]*)#([\w-]+)/' => function ($matches) {
                return ($matches[1] ? $matches[1] : '*').'[@id="'.$matches[2].'"]';
            },

            // .class
            // .class1..class2
            // E.class
            // E.class1..class2
            '/([\w-]*)\.([\w-]+)/' => function ($matches) {
                return ($matches[1] ? $matches[1] : '*').'[contains(concat(" ", normalize-space(@class), " "), " ' . $matches[2] . ' ")]';
            },

            '/\]\*/' => function () {
                return ']';
            },
        ];

        $xPath = '//'.preg_replace_callback_array($replacements, $selector);
        static::$cache[$selector] = $xPath;

        return $xPath;
    }
}

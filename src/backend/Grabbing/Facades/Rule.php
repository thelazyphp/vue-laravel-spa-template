<?php

namespace App\Grabbing\Facades;

/**
 * @method static mixed grab(\simple_html_dom|\simple_html_dom_node|mixed $src, mixed $default)
 * @method static \App\Grabbing\Rule default(mixed $value)
 * @method static \App\Grabbing\Rule map(callable $callback, mixed $default)
 * @method static \App\Grabbing\Rule find(string $selector, int|null $index)
 * @method static \App\Grabbing\Rule findAll(string $selector)
 * @method static \App\Grabbing\Rule findWithText(string $selector, mixed $value, int|null $index, bool $ignoreCase)
 * @method static \App\Grabbing\Rule findAllWithText(string $selector, mixed $value, bool $ignoreCase)
 * @method static \App\Grabbing\Rule findWithTextHas(string $selector, mixed $value, int|null $index, bool $ignoreCase)
 * @method static \App\Grabbing\Rule findAllWithTextHas(string $selector, mixed $value, bool $ignoreCase)
 * @method static \App\Grabbing\Rule findWithTextStarts(string $selector, mixed $value, int|null $index, bool $ignoreCase)
 * @method static \App\Grabbing\Rule findAllWithTextStarts(string $selector, mixed $value, bool $ignoreCase)
 * @method static \App\Grabbing\Rule findWithTextEnds(string $selector, mixed $value, int|null $index, bool $ignoreCase)
 * @method static \App\Grabbing\Rule findAllWithTextEnds(string $selector, mixed $value, bool $ignoreCase)
 * @method static \App\Grabbing\Rule findWithTextMatches(string $selector, string $pattern, int|null $index)
 * @method static \App\Grabbing\Rule findAllWithTextMatches(string $selector, string $pattern)
 * @method static \App\Grabbing\Rule nextSibling()
 * @method static \App\Grabbing\Rule prevSibling()
 * @method static \App\Grabbing\Rule parent(int $levels)
 * @method static \App\Grabbing\Rule children()
 * @method static \App\Grabbing\Rule child(int $index)
 * @method static \App\Grabbing\Rule firstChild()
 * @method static \App\Grabbing\Rule lastChild()
 * @method static \App\Grabbing\Rule innerHtml()
 * @method static \App\Grabbing\Rule outerHtml()
 * @method static \App\Grabbing\Rule html()
 * @method static \App\Grabbing\Rule innerText()
 * @method static \App\Grabbing\Rule text()
 * @method static \App\Grabbing\Rule attr(string|null $name)
 * @method static \App\Grabbing\Rule match(string $pattern, int $group, bool $all)
 * @method static \App\Grabbing\Rule matchAll(string $pattern, int $group)
 * @method static \App\Grabbing\Rule explode(string $delim)
 * @method static \App\Grabbing\Rule explodeByPattern(string $pattern)
 * @method static \App\Grabbing\Rule implode(string $delim)
 * @method static \App\Grabbing\Rule slice(int $offset, int|null $length)
 * @method static \App\Grabbing\Rule sort()
 * @method static \App\Grabbing\Rule filter(callable $callback)
 * @method static \App\Grabbing\Rule take(int $index)
 * @method static \App\Grabbing\Rule replace(string[] $value, string[] $replacement, bool $ignoreCase)
 * @method static \App\Grabbing\Rule replaceByPattern(string[] $pattern, string[] $replacement)
 * @method static \App\Grabbing\Rule takeDigits()
 * @method static \App\Grabbing\Rule takeInteger()
 * @method static \App\Grabbing\Rule takeFloat()
 * @method static \App\Grabbing\Rule takeNumeric()
 * @method static \App\Grabbing\Rule removeSpaces()
 * @method static \App\Grabbing\Rule removeDigits()
 * @method static \App\Grabbing\Rule removeHtmlEntities()
 * @method static \App\Grabbing\Rule trim()
 * @method static \App\Grabbing\Rule leftTrim()
 * @method static \App\Grabbing\Rule rightTrim()
 * @method static \App\Grabbing\Rule append(mixed $value)
 * @method static \App\Grabbing\Rule prepend(mixed $value)
 * @method static \App\Grabbing\Rule toLowerCase()
 * @method static \App\Grabbing\Rule toUpperCase()
 * @method static \App\Grabbing\Rule castTo(string $type)
 * @method static \App\Grabbing\Rule castToBoolean()
 * @method static \App\Grabbing\Rule castToInteger()
 * @method static \App\Grabbing\Rule castToFloat()
 * @method static \App\Grabbing\Rule castToArray()
 * @method static \App\Grabbing\Rule castToObject()
 * @method static \App\Grabbing\Rule castToTimestamp()
 * @method static \App\Grabbing\Rule castToDateTime(string $format)
 */
class Rule
{
    /**
     * @var string
     */
    protected static $accessor = 'App\Grabbing\Rule';

    /**
     * @param string $name
     * @param array $arguments
     *
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        return (new static::$accessor)->{$name}(...$arguments);
    }
}

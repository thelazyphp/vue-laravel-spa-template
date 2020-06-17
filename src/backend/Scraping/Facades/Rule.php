<?php

namespace App\Scraping\Facades;

use Closure;
use App\Scraping\Relationship;

/**
 * @method static mixed scrape(mixed $src, mixed $default)
 * @method static \App\Scraping\Rule default(mixed $value)
 * @method static \App\Scraping\Rule each(callable $callback, mixed $default)
 * @method static \App\Scraping\Rule find(string $selector, int|null $index)
 * @method static \App\Scraping\Rule findAll(string $selector)
 * @method static \App\Scraping\Rule findWithText(string $selector, mixed $value, int|null $index, bool $ignoreCase)
 * @method static \App\Scraping\Rule findAllWithText(string $selector, mixed $value, bool $ignoreCase)
 * @method static \App\Scraping\Rule findWithTextHas(string $selector, mixed $value, int|null $index, bool $ignoreCase)
 * @method static \App\Scraping\Rule findAllWithTextHas(string $selector, mixed $value, bool $ignoreCase)
 * @method static \App\Scraping\Rule findWithTextStarts(string $selector, mixed $value, int|null $index, bool $ignoreCase)
 * @method static \App\Scraping\Rule findAllWithTextStarts(string $selector, mixed $value, bool $ignoreCase)
 * @method static \App\Scraping\Rule findWithTextEnds(string $selector, mixed $value, int|null $index, bool $ignoreCase)
 * @method static \App\Scraping\Rule findAllWithTextEnds(string $selector, mixed $value, bool $ignoreCase)
 * @method static \App\Scraping\Rule findWithTextMatches(string $selector, string $pattern, int|null $index)
 * @method static \App\Scraping\Rule findAllWithTextMatches(string $selector, string $pattern)
 * @method static \App\Scraping\Rule nextSibling()
 * @method static \App\Scraping\Rule prevSibling()
 * @method static \App\Scraping\Rule parent(int $levels)
 * @method static \App\Scraping\Rule children()
 * @method static \App\Scraping\Rule child(int $index)
 * @method static \App\Scraping\Rule firstChild()
 * @method static \App\Scraping\Rule lastChild()
 * @method static \App\Scraping\Rule innerHtml()
 * @method static \App\Scraping\Rule outerHtml()
 * @method static \App\Scraping\Rule html()
 * @method static \App\Scraping\Rule innerText()
 * @method static \App\Scraping\Rule text()
 * @method static \App\Scraping\Rule attr(string|null $name)
 * @method static \App\Scraping\Rule match(string $pattern, int $group, bool $all)
 * @method static \App\Scraping\Rule matchAll(string $pattern, int $group)
 * @method static \App\Scraping\Rule explode(string $delim)
 * @method static \App\Scraping\Rule explodeByPattern(string $pattern)
 * @method static \App\Scraping\Rule implode(string $delim)
 * @method static \App\Scraping\Rule slice(int $offset, int|null $length)
 * @method static \App\Scraping\Rule sort()
 * @method static \App\Scraping\Rule filter(callable $callback)
 * @method static \App\Scraping\Rule take(int $index)
 * @method static \App\Scraping\Rule replace(string[] $value, string[] $replacement, bool $ignoreCase)
 * @method static \App\Scraping\Rule replaceByPattern(string[] $pattern, string[] $replacement)
 * @method static \App\Scraping\Rule takeDigits()
 * @method static \App\Scraping\Rule takeInteger()
 * @method static \App\Scraping\Rule takeFloat()
 * @method static \App\Scraping\Rule takeNumeric()
 * @method static \App\Scraping\Rule removeSpaces()
 * @method static \App\Scraping\Rule removeDigits()
 * @method static \App\Scraping\Rule removeHtmlEntities()
 * @method static \App\Scraping\Rule trim()
 * @method static \App\Scraping\Rule leftTrim()
 * @method static \App\Scraping\Rule rightTrim()
 * @method static \App\Scraping\Rule append(mixed $value)
 * @method static \App\Scraping\Rule prepend(mixed $value)
 * @method static \App\Scraping\Rule toLowerCase()
 * @method static \App\Scraping\Rule toUpperCase()
 * @method static \App\Scraping\Rule castTo(string $type)
 * @method static \App\Scraping\Rule castToBoolean()
 * @method static \App\Scraping\Rule castToInteger()
 * @method static \App\Scraping\Rule castToFloat()
 * @method static \App\Scraping\Rule castToArray()
 * @method static \App\Scraping\Rule castToObject()
 * @method static \App\Scraping\Rule castToTimestamp()
 * @method static \App\Scraping\Rule castToDateTime(string $format)
 */
class Rule
{
    /**
     * @var string
     */
    protected static $accessor = 'App\Scraping\Rule';

    /**
     * @param string $model
     * @param \Closure $rules
     * @param string[] $uniqueKeys
     *
     * @return \App\Scraping\Relationship
     */
    public static function relationship(
        $model,
        Closure $rules,
        $uniqueKeys = [])
    {
        return new Relationship($model, $rules, $uniqueKeys);
    }

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

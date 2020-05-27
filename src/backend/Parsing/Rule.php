<?php

namespace App\Parsing;

class Rule
{
    /**
     * @var bool
     */
    protected $definedAsConstValue = false;

    /**
     * @var mixed
     */
    protected $constValue;

    /**
     * @var mixed
     */
    protected $defaultValue = null;

    /**
     * @var array
     */
    protected $findRules = [];

    /**
     * @var array
     */
    protected $matchRules = [];

    /**
     * @var array
     */
    protected $replaceRules = [];

    /**
     * @var string
     */
    protected $property = 'plaintext';

    /**
     * @var string
     */
    protected $prepend = '';

    /**
     * @var string
     */
    protected $append = '';

    /**
     * @var string
     */
    protected $castType = 'string';

    /**
     * @var string
     */
    protected $dateTimeFormat;

    /**
     *
     */
    public function __construct()
    {
        //
    }

    // Getters:

    /**
     * @return bool
     */
    public function isDefinedAsConstValue()
    {
        return $this->definedAsConstValue;
    }

    /**
     * @return mixed
     */
    public function getConstValue()
    {
        return $this->constValue;
    }

    /**
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * @return array
     */
    public function getFindRules()
    {
        return $this->findRules;
    }

    /**
     * @return array
     */
    public function getMatchRules()
    {
        return $this->matchRules;
    }

    /**
     * @return array
     */
    public function getReplaceRules()
    {
        return $this->replaceRules;
    }

    /**
     * @return string
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * @return string
     */
    public function getPrepend()
    {
        return $this->prepend;
    }

    /**
     * @return string
     */
    public function getAppend()
    {
        return $this->append;
    }

    /**
     * @return string
     */
    public function getCastType()
    {
        return $this->castType;
    }

    /**
     * @return string
     */
    public function getDateTimeFormat()
    {
        return $this->dateTimeFormat;
    }

    // Setters:

    /**
     * @param  mixed  $value
     * @return self
     */
    public function defineAsConstValue($value)
    {
        $this->definedAsConstValue = true;
        $this->constValue = $value;

        return $this;
    }

    /**
     * @param  mixed  $value
     * @return self
     */
    public function defaultValue($value)
    {
        $this->defaultValue = $value;
        return $this;
    }

    /**
     * @param  string  $selector
     * @param  int|null  $index
     * @return self
     */
    public function find($selector, $index = null)
    {
        $type = 'selector';

        $this->findRules[] = compact(
            'type', 'selector', 'index'
        );

        return $this;
    }

    /**
     * @param  string  $selector
     * @return self
     */
    public function findAll($selector)
    {
        return $this->find($selector);
    }

    /**
     * @param  string  $selector
     * @return self
     */
    public function findFirst($selector)
    {
        return $this->find($selector, 0);
    }

    /**
     * @param  string  $selector
     * @return self
     */
    public function findLast($selector)
    {
        return $this->find($selector, -1);
    }

    /**
     * @param  int|null  $index
     * @return self
     */
    public function children($index = null)
    {
        $type = 'method';
        $params = [$index];
        $method = 'children';

        $this->findRules[] = compact(
            'type', 'params', 'method'
        );

        return $this;
    }

    /**
     * @return self
     */
    public function parent()
    {
        $type = 'method';
        $params = [];
        $method = 'parent';

        $this->findRules[] = compact(
            'type', 'params', 'method'
        );

        return $this;
    }

    /**
     * @return self
     */
    public function firstChild()
    {
        $type = 'method';
        $params = [];
        $method = 'first_child';

        $this->findRules[] = compact(
            'type', 'params', 'method'
        );

        return $this;
    }

    /**
     * @return self
     */
    public function lastChild()
    {
        $type = 'method';
        $params = [];
        $method = 'last_child';

        $this->findRules[] = compact(
            'type', 'params', 'method'
        );

        return $this;
    }

    /**
     * @return self
     */
    public function prevSibling()
    {
        $type = 'method';
        $params = [];
        $method = 'prev_sibling';

        $this->findRules[] = compact(
            'type', 'params', 'method'
        );

        return $this;
    }

    /**
     * @return self
     */
    public function nextSibling()
    {
        $type = 'method';
        $params = [];
        $method = 'next_sibling';

        $this->findRules[] = compact(
            'type', 'params', 'method'
        );

        return $this;
    }

    /**
     * @param  string  $selector
     * @param  string  $text
     * @param  int|null  $index
     * @return self
     */
    public function findWhereText($selector, $text, $index = null)
    {
        $type = 'where_text';

        $this->findRules[] = compact(
            'type', 'selector', 'text', 'index'
        );

        return $this;
    }

    /**
     * @param  string  $selector
     * @param  string  $text
     * @return self
     */
    public function findAllWhereText($selector, $text)
    {
        return $this->findWhereText($selector, $text);
    }

    /**
     * @param  string  $selector
     * @param  string  $text
     * @return self
     */
    public function findFirstWhereText($selector, $text)
    {
        return $this->findWhereText($selector, $text, 0);
    }

    /**
     * @param  string  $selector
     * @param  string  $text
     * @return self
     */
    public function findLastWhereText($selector, $text)
    {
        return $this->findWhereText($selector, $text, -1);
    }

    /**
     * @param  string  $selector
     * @param  string  $text
     * @param  int|null  $index
     * @return self
     */
    public function findWhereTextHas($selector, $text, $index = null)
    {
        $type = 'where_text_has';

        $this->findRules[] = compact(
            'type', 'selector', 'text', 'index'
        );

        return $this;
    }

    /**
     * @param  string  $selector
     * @param  string  $text
     * @return self
     */
    public function findAllWhereTextHas($selector, $text)
    {
        return $this->findWhereTextHas($selector, $text);
    }

    /**
     * @param  string  $selector
     * @param  string  $text
     * @return self
     */
    public function findFirstWhereTextHas($selector, $text)
    {
        return $this->findWhereTextHas($selector, $text, 0);
    }

    /**
     * @param  string  $selector
     * @param  string  $text
     * @return self
     */
    public function findLastWhereTextHas($selector, $text)
    {
        return $this->findWhereTextHas($selector, $text, -1);
    }

    /**
     * @param  string  $selector
     * @param  string  $text
     * @param  int|null  $index
     * @return self
     */
    public function findWhereTextStartsWith($selector, $text, $index = null)
    {
        $type = 'where_text_starts';

        $this->findRules[] = compact(
            'type', 'selector', 'text', 'index'
        );

        return $this;
    }

    /**
     * @param  string  $selector
     * @param  string  $text
     * @return self
     */
    public function findAllWhereTextStartsWith($selector, $text)
    {
        return $this->findWhereTextStartsWith($selector, $text);
    }

    /**
     * @param  string  $selector
     * @param  string  $text
     * @return self
     */
    public function findFirstWhereTextStartsWith($selector, $text)
    {
        return $this->findWhereTextStartsWith($selector, $text, 0);
    }

    /**
     * @param  string  $selector
     * @param  string  $text
     * @return self
     */
    public function findLastWhereTextStartsWith($selector, $text)
    {
        return $this->findWhereTextStartsWith($selector, $text, -1);
    }

    /**
     * @param  string  $selector
     * @param  string  $text
     * @param  int|null  $index
     * @return self
     */
    public function findWhereTextEndsWith($selector, $text, $index = null)
    {
        $type = 'where_text_ends';

        $this->findRules[] = compact(
            'type', 'selector', 'text', 'index'
        );

        return $this;
    }

    /**
     * @param  string  $selector
     * @param  string  $text
     * @return self
     */
    public function findAllWhereTextEndsWith($selector, $text)
    {
        return $this->findWhereTextEndsWith($selector, $text);
    }

    /**
     * @param  string  $selector
     * @param  string  $text
     * @return self
     */
    public function findFirstWhereTextEndsWith($selector, $text)
    {
        return $this->findWhereTextEndsWith($selector, $text, 0);
    }

    /**
     * @param  string  $selector
     * @param  string  $text
     * @return self
     */
    public function findLastWhereTextEndsWith($selector, $text)
    {
        return $this->findWhereTextEndsWith($selector, $text, -1);
    }

    /**
     * @return self
     */
    public function innerHtml()
    {
        $this->property = 'innertext';
        return $this;
    }

    /**
     * @return self
     */
    public function outerHtml()
    {
        $this->property = 'outertext';
        return $this;
    }

    /**
     * @return self
     */
    public function text()
    {
        $this->property = 'plaintext';
        return $this;
    }

    /**
     * @param  string  $name
     * @return self
     */
    public function attribute($name)
    {
        $this->property = $name;
        return $this;
    }

    /**
     * @param  string  $pattern
     * @param  int  $group
     * @return self
     */
    public function match($pattern, $group = 0)
    {
        $this->matchRules[] = compact(
            'pattern', 'group'
        );

        return $this;
    }

    /**
     * @param  string|string[]  $search
     * @param  string|string[]  $replace
     * @return self
     */
    public function replace($search, $replace)
    {
        $type = 'simple';

        $this->replaceRules[] = compact(
            'type', 'search', 'replace'
        );

        return $this;
    }

    /**
     * @param  string|string[]  $pattern
     * @param  string|string[]  $replacement
     * @return self
     */
    public function replaceMatched($pattern, $replacement)
    {
        $type = 'matched';

        $this->replaceRules[] = compact(
            'type', 'pattern', 'replacement'
        );

        return $this;
    }

    /**
     * @return self
     */
    public function takeNumeric()
    {
        return $this->match(
            '/[\d.,]+/'
        );
    }

    /**
     * @return self
     */
    public function takeDigits()
    {
        return $this->replaceMatched(
            '/\D/', ''
        );
    }

    /**
     * @return self
     */
    public function removeSpaces()
    {
        return $this->replaceMatched(
            '/\s/', ''
        );
    }

    /**
     * @return self
     */
    public function removeHtmlEntities()
    {
        return $this->replaceMatched(
            '/&[a-zA-Z]+;/', ''
        );
    }

    /**
     * @param  mixed  $value
     * @return self
     */
    public function prepend($value)
    {
        $this->prepend = $value;
        return $this;
    }

    /**
     * @param  mixed  $value
     * @return self
     */
    public function append($value)
    {
        $this->append = $value;
        return $this;
    }

    /**
     * @param  string  $type
     * @return self
     */
    public function castTo($type)
    {
        $this->castType = $type;
        return $this;
    }

    /**
     * @return self
     */
    public function castToBoolean()
    {
        return $this->castTo('bool');
    }

    /**
     * @return self
     */
    public function castToInteger()
    {
        return $this->castTo('int');
    }

    /**
     * @return self
     */
    public function castToFloat()
    {
        return $this->castTo('float');
    }

    /**
     * @return self
     */
    public function castToArray()
    {
        return $this->castTo('array');
    }

    /**
     * @return self
     */
    public function castToTimestamp()
    {
        return $this->castTo('timestamp');
    }

    /**
     * @param  string  $format
     * @return self
     */
    public function castToDate($format)
    {
        $this->dateTimeFormat = $format;
        return $this->castTo('date');
    }

    /**
     * @param  string  $format
     * @return self
     */
    public function castToTime($format)
    {
        $this->dateTimeFormat = $format;
        return $this->castTo('time');
    }

    /**
     * @param  string  $format
     * @return self
     */
    public function castToDateTime($format)
    {
        $this->dateTimeFormat = $format;
        return $this->castTo('date_time');
    }
}

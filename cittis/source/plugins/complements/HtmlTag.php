<?php

class HTMLTag
{
    protected $tag;
    protected $usesPrettyPrint = true;
    protected $isSingleton;
    protected $attributes = array();
    protected $children = array();
    protected $comments;
    protected $textComment = "";
    protected $exceptions = array("meta", 'link', "img", "hr", "input", "br");
    protected $exception = false;

    public function __construct($tag = '', $attributes = '', $pretty = true, $singleton = false, $spaced = true, $exception = false)
    {
        if (!is_string($tag) or !$this->isWord($tag)) {
            $this->throwError('Tag must have a single word String value.');
            return;
        }

        if (!is_bool($singleton)) {
            $this->throwError('Singleton parameter must have a Boolean value.');
            return;
        }

        if (!is_bool($pretty)) {
            $this->throwError('Pretty Print parameter must have a Boolean value.');
            return;
        }

        $this->tag = !empty($tag) ? $tag : 'span';
        $this->usesPrettyPrint = $pretty;
        $this->isSingleton = $singleton;

        switch (gettype($attributes)) {
            case 'object':
                {
                    $this->appendChild($attributes);
                    break;
                }

            case 'array':
                {
                    $this->createAttributes($attributes, $spaced);
                    break;
                }

            default:
                {
                    $this->innerHTML($attributes);
                    break;
                }
        }
        $this->exception = $exception;
        $this->setComment();
    }

    protected function isWord($string)
    {
        if (empty($string)) {
            return false;
        }

        if (!preg_match('/[a-z0-9:-_.]+/iS', $string)) {
            return false;
        }

        return true;
    }

    protected function throwError($error)
    {
        $backtrace = debug_backtrace();
        $line = $backtrace[1]['line'];

        throw new Exception(
            'Error on line ' . $line . ': ' . $error
        );
    }

    public function appendChild(HTMLTag $object)
    {
        if ($this->isSingleton === true) {
            $this->throwError('Singleton tags do not have children.');
            return false;
        }

        if (!is_object($object)) {
            $given_type = ucwords(gettype($object));
            $this->throwError($given_type . ' given, when Object is expected.');
            return false;
        }

        $this->children[] = $object;
        return true;
    }

    public function createAttributes(array $attributes, $spaced = true)
    {
        if (!is_array($attributes)) {
            $this->throwError('Attributes parameter must have an Array value.');
            return;
        }

        foreach ($attributes as $key => $value) {
            switch ($key) {
                case 'children':
                    {
                        if (is_array($value)) {
                            for ($i = 0, $il = count($value); $i < $il; $i++) {
                                $this->appendChild($value[$i]);
                            }
                        }

                        break;
                    }

                case 'innerHTML':
                    {
                        $this->innerHTML($value);
                        break;
                    }

                default:
                    {
                        $this->createAttribute($key, $value, $spaced);
                        break;
                    }
            }
        }
    }

    public function innerHTML($html = '')
    {
        if ($this->isSingleton === true) {
            $this->throwError('Singleton tags do not have innerHTML.');
            return false;
        }

        if (!empty($html)) {
            $this->children = array($html);
        }

        return true;
    }

    public function createAttribute($name = '', $value = '', $spaced = true)
    {
        if (!is_string($name) or !$this->isWord($name)) {
            $this->throwError('Attribute name must have a single word String value.');
            return false;
        }

        if (is_array($value)) {
            $glue = ($spaced !== false) ? ' ' : '';
            $this->attributes[$name] = implode($glue, $value);
            return true;
        }

        $this->attributes[$name] = $value;
        return true;
    }

    public function setComment($value = false, $text = '')
    {
        $this->comments = $value;
        if (nullOrEmptyString($text) && isset($text) && $text != '') {
            $this->setTextComment($text);
        }
    }

    public function setTextComment($text = '')
    {
        $this->textComment = $text;
    }

    public function __get($name)
    {
        switch ($name) {
            case 'innerHTML':
                {
                    return $this->getInnerHTML();
                }
        }
    }

    public function getInnerHTML($indention = '')
    {
        $inner_html = array();

        foreach ($this->children as $child) {
            if (is_object($child)) {
                $inner_html[] = $child->asHTML($indention);
                continue;
            }

            $inner_html[] = $child;
        }

        return implode(PHP_EOL . $indention, $inner_html);
    }

    public function appendAttributes(array $attributes, $spaced = true)
    {
        if (!is_array($attributes)) {
            $this->throwError('Attributes parameter must have an Array value.');
            return;
        }

        foreach ($attributes as $key => $value) {
            if ($key === 'innerHTML') {
                $this->appendInnerHTML($value);
                continue;
            }

            $this->appendAttribute($key, $value, $spaced);
        }
    }

    public function appendInnerHTML($html = '')
    {
        if ($this->isSingleton === true) {
            $this->throwError('Singleton tags do not have innerHTML.');
            return false;
        }

        if (!empty($html)) {
            $this->children[] = $html;
        }

        return true;
    }

    public function appendAttribute($name = '', $value = '', $spaced = true)
    {
        if (!is_string($name) or !$this->isWord($name)) {
            $this->throwError('Attribute name must have a single word String value.');
            return false;
        }

        if (!empty($this->attributes[$name])) {
            if ($spaced !== false) {
                $this->attributes[$name] .= ' ';
            }
        } else {
            $this->attributes[$name] = '';
        }

        if (is_array($value)) {
            $glue = ($spaced !== false) ? ' ' : '';
            $this->attributes[$name] .= implode($glue, $value);
            return true;
        }

        $this->attributes[$name] .= $value;
        return true;
    }

    public function asHTML($indention = '')
    {
        $attributes = '';

        foreach ($this->attributes as $name => $value) {
            $value = str_replace('"', '&quot;', $value);
            $attributes .= ' ' . $name . '="' . $value . '"';
        }

        $tag = "";
        if ($this->comments) {

            $tag .= $this->comment();
            $tag .= PHP_EOL;
        }
        $tag .= '<' . $this->tag . $attributes;

        $ex = $this->exception;
        if ($ex == false) {
            $ex = !in_array($this->tag, $this->exceptions);
        }

        if ($ex) {
            $tag .= '>';

            if ($this->isSingleton === false) {
                if (!empty($this->children)) {
                    $inner_html = $this->getInnerHTML();

                    if ($this->usesPrettyPrint === false) {
                        $tag .= PHP_EOL . "\t";
                        $tag .= str_replace(PHP_EOL, PHP_EOL . "\t", $inner_html);
                        $tag .= PHP_EOL;
                    } else {
                        if ($this->tag == "script") {
                            //$tag .= '/*<![CDATA[*/';
                            $tag .= '//<![CDATA[' .
                                PHP_EOL . $inner_html .
                                '//]]>';
                            //'/*]]>*/';
                        } else {
                            $tag .= $inner_html;
                        }
                    }
                }

                $tag .= '</' . $this->tag . '>';
                if ($this->comments) {
                    $tag .= PHP_EOL;
                    $tag .= $this->comment(1);
                }
            }
        } else {
            $tag .= '/>';
        }

        if (!empty($indention)) {
            return str_replace(PHP_EOL, PHP_EOL . $indention, $tag);
        }

        return $tag;
    }

    public function comment($position = 0)
    {
        $text = $this->tag;
        if (nullOrEmptyString($this->textComment) && isset($this->textComment) && $this->textComment != '') {
            $text = $this->textComment;
        }

        $end = "";

        if ($position === 1) {
            $end = "/";
        }

        $text = "<!-- " . $end . strtoupper($text) . " -->";
        return ($text);
    }
}

if (!function_exists('nullOrEmptyString')) {
    function nullOrEmptyString($question)
    {
        if (!is_array($question)) {
            return !(!isset($question) || trim($question) === '');
        } else {
            return !(!isset($question));
        }
    }
}

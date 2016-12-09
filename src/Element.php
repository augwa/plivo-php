<?php

namespace Plivo;

/**
 * Class Element
 * @package Plivo
 */
abstract class Element
{

    /** @var array */
    protected $nestable = [];

    /** @var array */
    protected $valid_attributes = [];

    /** @var array */
    protected $attributes = [];

    /** @var string */
    protected $name;

    /** @var null|string */
    protected $body = null;

    /** @var array  */
    protected $children = [];

    /**
     * Element constructor.
     *
     * @param string $body
     * @param array $attributes
     *
     * @throws PlivoError
     */
    function __construct(
        $body = '',
        $attributes = []
    )
    {
        $this->attributes = $attributes;
        if ((!$attributes) || ($attributes === null)) {
            $this->attributes = [];
        }

        $this->name = preg_replace('/^' . __NAMESPACE__ . '\\\\/', '', get_class($this));

        $this->body = $body;
        foreach ($this->attributes as $key => $value) {
            if (!in_array($key, $this->valid_attributes)) {
                throw new PlivoError("invalid attribute " . $key . " for " . $this->name);
            }
            $this->attributes[$key] = $this->convert_value($value);
        }
    }

    /**
     * @param mixed $v
     *
     * @return string
     */
    protected function convert_value($v)
    {
        if ($v === true) {
            return "true";
        }
        elseif ($v === false) {
            return "false";
        }
        elseif ($v === null) {
            return "none";
        }
        elseif ($v === "post" || $v === "get") {
            return strtoupper($v);
        }

        return $v;
    }

    /**
     * @param string $body
     * @param array $attributes
     *
     * @return Speak
     */
    function addSpeak(
        $body = '',
        $attributes = []
    )
    {
        return $this->add(new Speak($body, $attributes));
    }

    /**
     * @param string $body
     * @param array $attributes
     *
     * @return Play
     */
    function addPlay(
        $body = '',
        $attributes = []
    )
    {
        return $this->add(new Play($body, $attributes));
    }

    /**
     * @param array $attributes
     *
     * @return Dial
     */
    function addDial($attributes = [])
    {
        return $this->add(new Dial($attributes));
    }

    /**
     * @param string $body
     * @param array $attributes
     *
     * @return Number
     */
    function addNumber(
        $body = '',
        $attributes = []
    )
    {
        return $this->add(new Number($body, $attributes));
    }

    /**
     * @param string $body
     * @param array $attributes
     *
     * @return User
     */
    function addUser(
        $body = '',
        $attributes = []
    )
    {
        return $this->add(new User($body, $attributes));
    }

    /**
     * @param array $attributes
     *
     * @return GetDigits
     */
    function addGetDigits($attributes = [])
    {
        return $this->add(new GetDigits($attributes));
    }

    /**
     * @param array $attributes
     *
     * @return Record
     */
    function addRecord($attributes = [])
    {
        return $this->add(new Record($attributes));
    }

    /**
     * @param array $attributes
     *
     * @return Hangup
     */
    function addHangup($attributes = [])
    {
        return $this->add(new Hangup($attributes));
    }

    /**
     * @param string $body
     * @param array $attributes
     *
     * @return Redirect
     */
    function addRedirect(
        $body = '',
        $attributes = []
    )
    {
        return $this->add(new Redirect($body, $attributes));
    }

    /**
     * @param array $attributes
     *
     * @return Wait
     */
    function addWait($attributes = [])
    {
        return $this->add(new Wait($attributes));
    }

    /**
     * @param string $body
     * @param array $attributes
     *
     * @return Conference
     */
    function addConference(
        $body = '',
        $attributes = []
    )
    {
        return $this->add(new Conference($body, $attributes));
    }

    /**
     * @param array $attributes
     *
     * @return PreAnswer
     */
    function addPreAnswer($attributes = [])
    {
        return $this->add(new PreAnswer($attributes));
    }

    /**
     * @param string $body
     * @param array $attributes
     *
     * @return Message
     */
    function addMessage(
        $body = '',
        $attributes = []
    )
    {
        return $this->add(new Message($body, $attributes));
    }

    /**
     * @param string $body
     * @param array $attributes
     *
     * @return DTMF
     */
    function addDTMF(
        $body = '',
        $attributes = []
    )
    {
        return $this->add(new DTMF($body, $attributes));
    }

    /**
     * @return mixed|string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param Element $element
     *
     * @return mixed|Element
     * @throws PlivoError
     */
    protected function add(
        Element $element
    )
    {
        if (!in_array($element->getName(), $this->nestable)) {
            throw new PlivoError($element->getName() . " not nestable in " . $this->getName());
        }
        $this->children[] = $element;

        return $element;
    }

    /**
     * @param \SimpleXMLElement $xml
     */
    public function setAttributes(
        \SimpleXMLElement $xml
    )
    {
        foreach ($this->attributes as $key => $value) {
            $xml->addAttribute($key, $value);
        }
    }

    /**
     * @param \SimpleXMLElement $xml
     */
    public function asChild(
        \SimpleXMLElement $xml
    )
    {
        if ($this->body) {
            $child_xml = $xml->addChild($this->getName(), htmlspecialchars($this->body));
        }
        else {
            $child_xml = $xml->addChild($this->getName());
        }
        $this->setAttributes($child_xml);
        foreach ($this->children as $child) {
            $child->asChild($child_xml);
        }
    }

    /**
     * @param bool $header
     *
     * @return string|bool
     */
    public function toXML(
        $header = false
    )
    {
        if (!(isset($xmlStr))) {
            $xmlStr = '';
        }

        if ($this->body) {
            $xmlStr .= "<" . $this->getName() . ">" . htmlspecialchars($this->body) . "</" . $this->getName() . ">";
        }
        else {
            $xmlStr .= "<" . $this->getName() . "></" . $this->getName() . ">";
        }
        if ($header === true) {
            $xmlStr = "<?xml version=\"1.0\" encoding=\"utf-8\" ?>" . $xmlStr;
        }
        $xml = new \SimpleXMLElement($xmlStr);
        $this->setAttributes($xml);
        foreach ($this->children as $child) {
            $child->asChild($xml);
        }

        return $xml->asXML();
    }

    /**
     * @return string|bool
     */
    public function __toString()
    {
        return $this->toXML();
    }

}
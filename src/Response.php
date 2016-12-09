<?php

namespace Plivo;

/**
 * Class Response
 * @package Plivo
 */
class Response
    extends Element
{

    /** @var array */
    protected $nestable = [
        'Speak',
        'Play',
        'GetDigits',
        'Record',
        'Dial',
        'Redirect',
        'Wait',
        'Hangup',
        'PreAnswer',
        'Conference',
        'DTMF',
        'Message'
    ];

    /**
     * Response constructor.
     */
    function __construct()
    {
        parent::__construct(null);
    }

    /**
     * @param bool $header
     *
     * @return bool|string
     */
    public function toXML($header = false)
    {
        return parent::toXML(true);
    }
}
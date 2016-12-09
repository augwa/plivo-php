<?php

namespace Plivo;

/**
 * Class PreAnswer
 * @package Plivo
 */
class PreAnswer
    extends Element
{

    /** @var array */
    protected $nestable = [
        'Play',
        'Speak',
        'GetDigits',
        'Wait',
        'Redirect',
        'Message',
        'DTMF'
    ];

    /**
     * PreAnswer constructor.
     *
     * @param array $attributes
     */
    function __construct($attributes = [])
    {
        parent::__construct(null, $attributes);
    }
}
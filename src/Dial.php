<?php

namespace Plivo;

/**
 * Class Dial
 * @package Plivo
 */
class Dial
    extends Element
{

    /** @var array */
    protected $nestable = [
        'Number',
        'User'
    ];

    /** @var array */
    protected $valid_attributes = [
        'action',
        'method',
        'timeout',
        'hangupOnStar',
        'timeLimit',
        'callerId',
        'callerName',
        'confirmSound',
        'dialMusic',
        'confirmKey',
        'redirect',
        'callbackUrl',
        'callbackMethod',
        'digitsMatch',
        'digitsMatchBLeg',
        'sipHeaders'
    ];

    /**
     * Dial constructor.
     *
     * @param array $attributes
     */
    function __construct($attributes = [])
    {
        parent::__construct(null, $attributes);
    }
}
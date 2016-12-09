<?php

namespace Plivo;

/**
 * Class Wait
 * @package Plivo
 */
class Wait
    extends Element
{

    /** @var array */
    protected $valid_attributes = [
        'length',
        'silence',
        'min_silence',
        'minSilence',
        'beep'
    ];

    /**
     * Wait constructor.
     *
     * @param array $attributes
     */
    function __construct($attributes = [])
    {
        parent::__construct(null, $attributes);
    }
}
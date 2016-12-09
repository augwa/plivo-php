<?php

namespace Plivo;

/**
 * Class Speak
 * @package Plivo
 */
class Speak
    extends Element
{

    /** @var array */
    protected $valid_attributes = [
        'voice',
        'language',
        'loop'
    ];

    /**
     * Speak constructor.
     *
     * @param string $body
     * @param array $attributes
     *
     * @throws PlivoError
     */
    function __construct(
        $body,
        $attributes = []
    )
    {
        if (!$body) {
            throw new PlivoError("No text set for " . $this->getName());
        }
        $body = mb_encode_numericentity(
            $body,
            [
                0x80,
                0xffff,
                0,
                0xffff
            ]
        );
        parent::__construct($body, $attributes);
    }
}
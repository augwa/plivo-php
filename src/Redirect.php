<?php

namespace Plivo;

/**
 * Class Redirect
 * @package Plivo
 */
class Redirect
    extends Element
{

    /** @var array */
    protected $valid_attributes = ['method'];

    /**
     * Redirect constructor.
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
            throw new PlivoError("No url set for " . $this->getName());
        }
        parent::__construct($body, $attributes);
    }
}
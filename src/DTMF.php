<?php

namespace Plivo;

/**
 * Class DTMF
 * @package Plivo
 */
class DTMF
    extends Element
{

    /** @var array  */
    protected $valid_attributes = ['async'];

    /**
     * DTMF constructor.
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
            throw new PlivoError("No digits set for " . $this->getName());
        }
        parent::__construct($body, $attributes);
    }
}
<?php

namespace Plivo;

/**
 * Class Conference
 * @package Plivo
 */
class Conference
    extends Element
{

    /** @var array */
    protected $valid_attributes = [
        'muted',
        'beep',
        'startConferenceOnEnter',
        'endConferenceOnExit',
        'waitSound',
        'enterSound',
        'exitSound',
        'timeLimit',
        'hangupOnStar',
        'maxMembers',
        'record',
        'recordFileFormat',
        'recordWhenAlone',
        'action',
        'method',
        'redirect',
        'digitsMatch',
        'callbackUrl',
        'callbackMethod',
        'stayAlone',
        'floorEvent',
        'transcriptionType',
        'transcriptionUrl',
        'transcriptionMethod',
        'relayDTMF'
    ];

    /**
     * Conference constructor.
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
            throw new PlivoError("No conference name set for " . $this->getName());
        }
        parent::__construct($body, $attributes);
    }
}
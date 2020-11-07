<?php

declare(strict_types=1);

namespace Frmoda\EventsApiBundle\OuterEvent;

class OuterEventBuilder
{
    /** @var array */
    private $payload = [];

    public function withPayload(array $payload): self
    {
        $this->payload = $payload;
        return $this;
    }

    /**
     * @param string $eventName
     * @return OuterEvent
     */
    public function build(string $eventName): OuterEvent
    {
        $outerEvent = new OuterEvent($eventName, $this->payload, time());

        $this->payload = [];

        return $outerEvent;
    }
}

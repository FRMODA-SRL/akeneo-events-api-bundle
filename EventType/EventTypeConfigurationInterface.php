<?php

declare(strict_types=1);

namespace Frmoda\EventsApiBundle\EventType;

use Frmoda\EventsApiBundle\Model\GenericEventInterface;

interface EventTypeConfigurationInterface
{
    /**
     * @param GenericEventInterface $event
     * @return EventType
     * @throws EventIsNotSupportedException
     */
    public function resolve(GenericEventInterface $event): EventType;
}

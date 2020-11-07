<?php

declare(strict_types=1);

namespace Frmoda\EventsApiBundle\Transport;

use Frmoda\EventsApiBundle\OuterEvent\OuterEvent;

interface Transport
{
    /**
     * @param OuterEvent $event
     */
    public function deliver(OuterEvent $event): void;
}

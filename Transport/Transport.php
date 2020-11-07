<?php

declare(strict_types=1);

namespace Frmoda\Bundle\EventsApiBundle\Transport;

use Frmoda\Bundle\EventsApiBundle\OuterEvent\OuterEvent;

interface Transport
{
    /**
     * @param OuterEvent $event
     */
    public function deliver(OuterEvent $event): void;
}

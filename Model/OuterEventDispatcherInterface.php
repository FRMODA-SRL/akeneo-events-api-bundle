<?php

declare(strict_types=1);

namespace Frmoda\EventsApiBundle\Model;

use Frmoda\EventsApiBundle\OuterEvent\OuterEvent;

interface OuterEventDispatcherInterface
{
    /**
     * @param OuterEvent $event
     */
    public function dispatch(OuterEvent $event): void;
}

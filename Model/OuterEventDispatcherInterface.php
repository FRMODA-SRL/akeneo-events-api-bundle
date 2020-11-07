<?php

declare(strict_types=1);

namespace Frmoda\Bundle\EventsApiBundle\Model;

use Frmoda\Bundle\EventsApiBundle\OuterEvent\OuterEvent;

interface OuterEventDispatcherInterface
{
    /**
     * @param OuterEvent $event
     */
    public function dispatch(OuterEvent $event): void;
}

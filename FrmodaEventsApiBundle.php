<?php

namespace Frmoda\Bundle\EventsApiBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Frmoda\Bundle\EventsApiBundle\DependencyInjection\Compiler\RegisterEventTypeConfigurationsPass;

class FrmodaEventsApiBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new RegisterEventTypeConfigurationsPass());
    }
}

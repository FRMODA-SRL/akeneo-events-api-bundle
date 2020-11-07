<?php

namespace Frmoda\EventsApiBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Frmoda\EventsApiBundle\DependencyInjection\Compiler\RegisterEventTypeConfigurationsPass;

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

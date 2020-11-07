<?php

declare(strict_types=1);

namespace Frmoda\EventsApiBundle\Job;

use Akeneo\Tool\Component\Batch\Model\StepExecution;
use Akeneo\Tool\Component\Connector\Step\TaskletInterface;
use Frmoda\EventsApiBundle\Job\JobParameters\DeliverOuterEventConstraintCollectionProvider;
use Frmoda\EventsApiBundle\OuterEvent\OuterEvent;
use Frmoda\EventsApiBundle\Transport\Transport;

class DeliverOuterEventTasklet implements TaskletInterface
{
    /** @var Transport */
    private $transport;

    /** @var StepExecution */
    private $stepExecution;

    /**
     * DeliverOuterEventToConsumerTasklet constructor.
     * @param Transport $transport
     */
    public function __construct(Transport $transport)
    {
        $this->transport = $transport;
    }

    /**
     * {@inheritdoc}
     */
    public function setStepExecution(StepExecution $stepExecution): void
    {
        $this->stepExecution = $stepExecution;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(): void
    {
        $outerEventJson = $this->stepExecution->getJobParameters()
            ->get(DeliverOuterEventConstraintCollectionProvider::JOB_PARAMETER_KEY_OUTER_EVENT);

        $this->transport->deliver(OuterEvent::fromArray($outerEventJson));
    }
}

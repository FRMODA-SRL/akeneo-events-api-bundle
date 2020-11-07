<?php

declare(strict_types=1);

namespace Frmoda\Bundle\EventsApiBundle\Tests\Unit\Job;

use Akeneo\Tool\Component\Batch\Job\JobParameters;
use Akeneo\Tool\Component\Batch\Model\JobExecution;
use Akeneo\Tool\Component\Batch\Model\StepExecution;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Frmoda\Bundle\EventsApiBundle\Job\DeliverOuterEventTasklet;
use Frmoda\Bundle\EventsApiBundle\OuterEvent\OuterEvent;
use Frmoda\Bundle\EventsApiBundle\Transport\Transport;

class DeliverOuterEventTaskletTest extends TestCase
{
    /**
     * @test
     */
    public function executes(): void
    {
        $event = new OuterEvent('foo_event', ['foo' => 'payload'], time());

        /** @var Transport|MockObject $transport */
        $transport = $this->createMock(Transport::class);
        $transport->expects(self::once())->method('deliver')
            ->with($event);

        $tasklet = new DeliverOuterEventTasklet($transport);

        $tasklet->setStepExecution(
            $this->createStepExecution(new JobParameters(['outer_event' => $event->toArray()]))
        );

        $tasklet->execute();
    }

    /**
     * @param JobParameters $jobParameters
     * @return StepExecution
     */
    private function createStepExecution(JobParameters $jobParameters): StepExecution
    {
        return new StepExecution(
            'foo',
            (new JobExecution())->setJobParameters($jobParameters)
        );
    }
}

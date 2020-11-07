<?php

declare(strict_types=1);

namespace Frmoda\Bundle\EventsApiBundle\Tests\Unit\Transport;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Frmoda\Bundle\EventsApiBundle\HttpClient\HttpClientFactoryInterface;
use Frmoda\Bundle\EventsApiBundle\HttpClient\RequestFactoryInterface;
use Frmoda\Bundle\EventsApiBundle\OuterEvent\OuterEvent;
use Frmoda\Bundle\EventsApiBundle\Transport\IFTTTWebHooksTransport;

class IFTTTWebHooksTransportTest extends TestCase
{
    /**
     * @test
     */
    public function deliversOuterEventToIFTTTWebHooks(): void
    {
        $outerEvent = new OuterEvent('foo_name', ['foo' => 'payload'], time());
        $requestUrl = 'https://iftt.com/a/b/{event}/x/y';

        /** @var ClientInterface|MockObject $httpClient */
        $httpClient = $this->createMock(ClientInterface::class);
        $request = $this->createMock(RequestInterface::class);
        /** @var HttpClientFactoryInterface|MockObject $httpClientFactory */
        $httpClientFactory = $this->createMock(HttpClientFactoryInterface::class);
        /** @var RequestFactoryInterface|MockObject $requestFactory */
        $requestFactory = $this->createMock(RequestFactoryInterface::class);

        $httpClientFactory->expects(self::once())->method('create')
            ->with('https://iftt.com/a/b/foo_name/x/y')->willReturn($httpClient);
        $requestFactory->expects(self::once())->method('create')
            ->with('POST', '', ['Content-Type' => 'application/json'], json_encode(['value1' => 'foo_name', 'value2' => ['foo' => 'payload']]))
            ->willReturn($request);
        $httpClient->expects(self::once())->method('sendRequest')->with($request);

        $transport = new IFTTTWebHooksTransport($requestUrl, $httpClientFactory, $requestFactory);

        $transport->deliver($outerEvent);
    }
}

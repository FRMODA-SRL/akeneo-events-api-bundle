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
use Frmoda\Bundle\EventsApiBundle\Transport\HttpTransport;

class HttpTransportTest extends TestCase
{
    /**
     * @test
     */
    public function deliversOuterEventViaHttpClient(): void
    {
        $outerEvent = new OuterEvent('foo_event', ['foo' => 'payload'], time());

        /** @var ClientInterface|MockObject $httpClient */
        $httpClient = $this->createMock(ClientInterface::class);
        $request = $this->createMock(RequestInterface::class);
        /** @var HttpClientFactoryInterface|MockObject $httpClientFactory */
        $httpClientFactory = $this->createMock(HttpClientFactoryInterface::class);
        /** @var RequestFactoryInterface|MockObject $requestFactory */
        $requestFactory = $this->createMock(RequestFactoryInterface::class);

        $httpClientFactory->expects(self::once())->method('create')
            ->with('http://localhost:1234')->willReturn($httpClient);
        $requestFactory->expects(self::once())->method('create')
            ->with('POST', '', ['Content-Type' => 'application/json'], json_encode($outerEvent))
            ->willReturn($request);
        $httpClient->expects(self::once())->method('sendRequest')->with($request);

        $httpTransport = new HttpTransport('http://localhost:1234', $httpClientFactory, $requestFactory);

        $httpTransport->deliver($outerEvent);
    }
}

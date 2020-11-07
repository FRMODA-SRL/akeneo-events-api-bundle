<?php

declare(strict_types=1);

namespace Frmoda\EventsApiBundle\Tests\Unit\Transport;

use Assert\InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Frmoda\EventsApiBundle\HttpClient\HttpClientFactoryInterface;
use Frmoda\EventsApiBundle\HttpClient\RequestFactoryInterface;
use Frmoda\EventsApiBundle\Transport\HttpTransport;
use Frmoda\EventsApiBundle\Transport\HttpTransportFactory;

class HttpTransportFactoryTest extends TestCase
{
    /**
     * @test
     * @dataProvider invalidOptionsDataProvider
     * @param array $options
     */
    public function throwsExceptionIfOptionsAreInvalid(array $options): void
    {
        $this->expectException(InvalidArgumentException::class);

        /** @var HttpClientFactoryInterface|MockObject $httpClientFactory */
        $httpClientFactory = $this->createMock(HttpClientFactoryInterface::class);
        /** @var RequestFactoryInterface|MockObject $requestFactory */
        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $factory = new HttpTransportFactory($httpClientFactory, $requestFactory);

        $factory->create($options);
    }

    /**
     * @test
     */
    public function createsHttpTransport(): void
    {
        /** @var HttpClientFactoryInterface|MockObject $httpClientFactory */
        $httpClientFactory = $this->createMock(HttpClientFactoryInterface::class);
        /** @var RequestFactoryInterface|MockObject $requestFactory */
        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $factory = new HttpTransportFactory($httpClientFactory, $requestFactory);

        $transport = $factory->create(['request_url' => 'http://foo.bar']);

        self::assertInstanceOf(HttpTransport::class, $transport);
    }

    public function invalidOptionsDataProvider(): array
    {
        return [
            [ [] ],
            [ ['foo_param'] ],
            [ ['foo_param' => 'value'] ],
            [ ['request_url' => ''] ],
            [ ['request_url' => 'url_value'] ],
        ];
    }
}

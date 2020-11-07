<?php

declare(strict_types=1);

namespace Frmoda\Bundle\EventsApiBundle\Tests\Unit\Guzzle;

use Assert\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Frmoda\Bundle\EventsApiBundle\Guzzle\GuzzleHttpClientAdapter;
use Frmoda\Bundle\EventsApiBundle\Guzzle\GuzzleHttpClientFactory;

class GuzzleHttpClientFactoryTest extends TestCase
{
    /**
     * @test
     * @dataProvider baseUriDataProvider
     * @param string $baseUri
     */
    public function throwsExceptionIfBaseUriIsInvalid(string $baseUri): void
    {
        $this->expectException(InvalidArgumentException::class);

        $factory = new GuzzleHttpClientFactory();
        $factory->create($baseUri);
    }

    /**
     * @test
     */
    public function createsGuzzleHttpClientAdapter(): void
    {
        $factory = new GuzzleHttpClientFactory();
        $client = $factory->create('http://127.0.0.1');

        self::assertInstanceOf(GuzzleHttpClientAdapter::class, $client);
    }

    public function baseUriDataProvider(): array
    {
        return [
            [''],
            ['foo_host']
        ];
    }
}

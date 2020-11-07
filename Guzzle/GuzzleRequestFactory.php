<?php

declare(strict_types=1);

namespace Frmoda\EventsApiBundle\Guzzle;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Frmoda\EventsApiBundle\HttpClient\RequestFactoryInterface;

class GuzzleRequestFactory implements RequestFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function create(string $method, string $uri, array $headers = [], string $body = null): RequestInterface
    {
        return new Request($method, $uri, $headers, $body);
    }
}

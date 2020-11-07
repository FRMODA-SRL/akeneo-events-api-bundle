<?php

declare(strict_types=1);

namespace Frmoda\Bundle\EventsApiBundle\Transport;

use Frmoda\Bundle\EventsApiBundle\HttpClient\HttpClientFactoryInterface;
use Frmoda\Bundle\EventsApiBundle\HttpClient\RequestFactoryInterface;
use Frmoda\Bundle\EventsApiBundle\OuterEvent\OuterEvent;

class IFTTTWebHooksTransport implements Transport
{
    /** @var string */
    private $requestUrl;

    /** @var HttpClientFactoryInterface */
    private $httpClientFactory;

    /** @var RequestFactoryInterface */
    private $requestFactory;

    /**
     * IFTTTWebHooksTransport constructor.
     * @param string $requestUrl
     * @param HttpClientFactoryInterface $httpClientFactory
     * @param RequestFactoryInterface $requestFactory
     */
    public function __construct(
        string $requestUrl,
        HttpClientFactoryInterface $httpClientFactory,
        RequestFactoryInterface $requestFactory
    ) {
        $this->requestUrl = $requestUrl;
        $this->httpClientFactory = $httpClientFactory;
        $this->requestFactory = $requestFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function deliver(OuterEvent $event): void
    {
        $requestUrl = str_replace('{event}', $event->eventType(), $this->requestUrl);
        $client = $this->httpClientFactory->create($requestUrl);
        $request = $this->requestFactory->create(
            'POST',
            '',
            ['Content-Type' => 'application/json'],
            json_encode(
                [
                    'value1' => $event->eventType(),
                    'value2' => $event->payload()
                ]
            )
        );
        $client->sendRequest($request);
    }
}

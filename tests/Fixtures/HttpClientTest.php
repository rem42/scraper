<?php

namespace Scraper\Scraper\Tests\Fixtures;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\ResponseStreamInterface;

final class HttpClientTest implements HttpClientInterface
{
    private ResponseInterface $response;
    private ResponseStreamInterface $responseStream;
    private array $options;

    public function __construct(ResponseInterface $response, ResponseStreamInterface $responseStream)
    {
        $this->response       = $response;
        $this->responseStream = $responseStream;
    }

    /**
     * @return mixed[]
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    public function request(string $method, string $url, array $options = []): ResponseInterface
    {
        $this->options = $options;
        return $this->response;
    }

    public function stream($responses, float $timeout = null): ResponseStreamInterface
    {
        return $this->responseStream;
    }
}

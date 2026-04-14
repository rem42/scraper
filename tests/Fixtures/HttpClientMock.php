<?php

declare(strict_types=1);

namespace Scraper\Scraper\Tests\Fixtures;

use PHPUnit\Framework\Attributes\CoversNothing;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\ResponseStreamInterface;

#[CoversNothing]
final class HttpClientMock implements HttpClientInterface
{
    private array $options;

    public function __construct(
        private ResponseInterface $response,
        private ResponseStreamInterface $responseStream,
    ) {}

    public function getOptions(): array
    {
        return $this->options;
    }

    public function request(string $method, string $url, array $options = []): ResponseInterface
    {
        $this->options = $options;

        return $this->response;
    }

    public function stream($responses, ?float $timeout = null): ResponseStreamInterface
    {
        return $this->responseStream;
    }

    public function withOptions(array $options): static
    {
        $self = new self(
            $this->response,
            $this->responseStream
        );
        $self->options = $options;

        return $self;
    }
}

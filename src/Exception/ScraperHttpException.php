<?php

declare(strict_types=1);

namespace Scraper\Scraper\Exception;

class ScraperHttpException extends ScraperException
{
    public function __construct(
        string $message,
        private readonly int $statusCode,
        private readonly string $url,
        private readonly string $responseContent = '',
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $statusCode, $previous);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getResponseContent(): string
    {
        return $this->responseContent;
    }
}

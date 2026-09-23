<?php

declare(strict_types=1);

namespace Scraper\Scraper\Dto;

use Scraper\Scraper\Attribute\Method;
use Scraper\Scraper\Attribute\Scheme;

final readonly class ScraperConfig
{
    public function __construct(
        public Method $method,
        public Scheme $scheme,
        public string $host,
        public string $path = '',
    ) {}

    public function getMethod(): string
    {
        return $this->method->value;
    }

    public function url(): string
    {
        $url = strtolower($this->scheme->value) . '://';
        $url .= rtrim($this->host, '/') . '/';

        if ('' !== $this->path) {
            $url .= ltrim($this->path, '/');
        }

        return $url;
    }
}

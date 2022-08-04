<?php declare(strict_types=1);

namespace Scraper\Scraper\Attribute;

use Scraper\Scraper\Exception\ScraperException;

#[\Attribute(\Attribute::TARGET_CLASS)]
final class Scraper
{
    public function __construct(
        public ?Method $method = null,
        public ?Scheme $scheme = null,
        public ?string $host = null,
        public ?string $path = null
    ) {}

    public function getMethod(): string
    {
        if (null === $this->method) {
            throw new ScraperException('Method not found');
        }

        return $this->method->value;
    }

    public function url(): string
    {
        if (!$this->scheme) {
            throw new ScraperException('scheme is required');
        }
        $url = strtolower($this->scheme->value) . '://';

        if (!$this->host) {
            throw new ScraperException('host is required');
        }
        $url .= rtrim($this->host, '/') . '/';

        if ($this->path) {
            $url .= '/' . ltrim($this->path, '/');
        }
        return $url;
    }
}

<?php

declare(strict_types=1);

namespace Scraper\Scraper\Attribute;

#[\Attribute(\Attribute::TARGET_CLASS)]
final class Scraper
{
    public function __construct(
        public ?Method $method = null,
        public ?Scheme $scheme = null,
        public ?string $host = null,
        public ?string $path = null,
    ) {}
}

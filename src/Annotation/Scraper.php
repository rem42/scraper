<?php

namespace Scraper\Scraper\Annotation;

use Doctrine\Common\Annotations\Annotation\Enum;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotations
 * @Target("CLASS")
 */
final class Scraper
{
    /** @Enum({"HTTP", "HTTPS"}) */
    public string $scheme;
    public string $host;
    public string $path;

    /** @Enum({"POST", "GET", "PUT"}) */
    public string $method;

    public function url(): string
    {
        return strtolower($this->scheme) . '://' . rtrim($this->host, '/') . '/' . ltrim($this->path, '/');
    }
}

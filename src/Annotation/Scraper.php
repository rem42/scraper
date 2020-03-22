<?php

namespace Scraper\Scraper\Annotation;

use Doctrine\Common\Annotations\Annotation\Enum;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotations
 * @Target("CLASS")
 */
class Scraper
{
    /** @Enum({"HTTP", "HTTPS"}) */
    public string $scheme = 'HTTPS';
    public string $host;
    public int $port;
    public string $path;

    /** @Enum({"POST", "GET", "PUT"}) */
    public string $method;
    public string $contentType;
    /** @Enum("SOAP")  */
    public string $protocol;

    public string $responseAdapter;

    public function url(): string
    {
        return strtolower($this->scheme) . '://' . rtrim($this->host, '/') . '/' . ltrim($this->path, '/');
    }
}

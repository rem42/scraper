<?php

namespace Scraper\Scraper\Tests\Fixtures;

use Scraper\Scraper\Annotation\Scraper;
use Scraper\Scraper\Request\ScraperRequest;

/**
 * @Scraper(host="host-test.{ndd}", path="path/to/{endpoint}", method="GET", scheme="HTTPS", port="443")
 */
final class TestWithAnnotationParametersRequest extends ScraperRequest
{
    private string $endpoint;
    private string $ndd;

    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    public function setEndpoint(string $endpoint): self
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    public function getNdd(): string
    {
        return $this->ndd;
    }

    public function setNdd(string $ndd): self
    {
        $this->ndd = $ndd;

        return $this;
    }
}

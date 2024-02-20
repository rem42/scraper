<?php declare(strict_types=1);

namespace Scraper\Scraper\Tests\Fixtures;

use Scraper\Scraper\Attribute\Method;
use Scraper\Scraper\Attribute\Scheme;
use Scraper\Scraper\Attribute\Scraper;
use Scraper\Scraper\Request\ScraperRequest;

#[Scraper(method: Method::GET, scheme: Scheme::HTTPS, host: 'host-test.{ndd}', path: 'path/to/{endpoint}')]
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

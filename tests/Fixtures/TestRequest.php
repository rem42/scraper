<?php

namespace Scraper\Scraper\Tests\Fixtures;

use Scraper\Scraper\Annotation\Scraper;
use Scraper\Scraper\Request\RequestBearer;
use Scraper\Scraper\Request\RequestBody;
use Scraper\Scraper\Request\RequestHeaders;
use Scraper\Scraper\Request\RequestQuery;
use Scraper\Scraper\Request\ScraperRequest;

/**
 * @Scraper(host="host-test.api", path="path/to/endpoint", method="GET", scheme="HTTPS", port="443")
 */
final class TestRequest extends ScraperRequest implements RequestBearer, RequestBody, RequestHeaders, RequestQuery
{
    public function getBearer(): string
    {
        return 'bearerToken';
    }

    public function getBody(): string
    {
        return 'body';
    }

    /**
     * @return string[]
     */
    public function getHeaders(): array
    {
        return [
            'custom-header' => 'header',
        ];
    }

    /**
     * @return string[]
     */
    public function getQuery(): array
    {
        return [
            'custom-query' => 'query',
        ];
    }
}

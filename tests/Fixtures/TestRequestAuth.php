<?php declare(strict_types=1);

namespace Scraper\Scraper\Tests\Fixtures;

use Scraper\Scraper\Annotation\Scraper as ScraperAnnotation;
use Scraper\Scraper\Attribute\Method;
use Scraper\Scraper\Attribute\Scheme;
use Scraper\Scraper\Attribute\Scraper;
use Scraper\Scraper\Request\RequestAuthBearer;
use Scraper\Scraper\Request\RequestBody;
use Scraper\Scraper\Request\RequestHeaders;
use Scraper\Scraper\Request\RequestQuery;
use Scraper\Scraper\Request\ScraperRequest;

/**
 * @ScraperAnnotation(host="host-test.api", path="path/to/endpoint", method="GET", scheme="HTTPS")
 */
#[Scraper(method: Method::GET, scheme: Scheme::HTTPS, host: 'host-test.api', path: 'path/to/endpoint')]
final class TestRequestAuth extends ScraperRequest implements RequestAuthBearer, RequestBody, RequestHeaders, RequestQuery
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
     * @return array<string>
     */
    public function getHeaders(): array
    {
        return [
            'custom-header' => 'header',
        ];
    }

    /**
     * @return array<string>
     */
    public function getQuery(): array
    {
        return [
            'custom-query' => 'query',
        ];
    }
}

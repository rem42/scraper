<?php declare(strict_types=1);

namespace Scraper\Scraper\Tests\Fixtures;

use Scraper\Scraper\Attribute\Method;
use Scraper\Scraper\Attribute\Scheme;
use Scraper\Scraper\Attribute\Scraper;
use Scraper\Scraper\Request\ScraperRequest;

#[Scraper(method: Method::GET, scheme: Scheme::HTTPS, host: 'host-test.api', path: 'path/to/endpoint')]
final class TestWithoutApiFileRequest extends ScraperRequest
{
}

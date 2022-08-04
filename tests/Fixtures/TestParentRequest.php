<?php declare(strict_types=1);

namespace Scraper\Scraper\Tests\Fixtures;

use Scraper\Scraper\Annotation\Scraper as ScraperAnnotation;
use Scraper\Scraper\Attribute\Method;
use Scraper\Scraper\Attribute\Scheme;
use Scraper\Scraper\Attribute\Scraper;
use Scraper\Scraper\Request\ScraperRequest;

/**
 * @ScraperAnnotation(host="host-test.api", path="path/to/endpoint", method="GET", scheme="HTTPS")
 */
#[Scraper(method: Method::GET, scheme: Scheme::HTTPS, host: 'host-test.api', path: 'path/to/endpoint')]
abstract class TestParentRequest extends ScraperRequest
{
}

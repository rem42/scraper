<?php declare(strict_types=1);

namespace Scraper\Scraper\Tests\Fixtures;

use Scraper\Scraper\Annotation\Scraper;
use Scraper\Scraper\Request\ScraperRequest;

/**
 * @Scraper(host="host-test.api", path="path/to/endpoint", method="GET", scheme="HTTPS")
 */
abstract class TestParentRequest extends ScraperRequest
{
}

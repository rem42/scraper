<?php

namespace Scraper\Scraper\Tests\Fixtures;

use Scraper\Scraper\Annotation\Scraper;
use Scraper\Scraper\Request\ScraperRequest;

/**
 * @Scraper(host="host-test.api", path="path/to/endpoint", method="GET", scheme="HTTPS", port="443")
 */
final class TestWithoutApiFileRequest extends ScraperRequest
{
}

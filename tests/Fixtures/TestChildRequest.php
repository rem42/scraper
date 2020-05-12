<?php

namespace Scraper\Scraper\Tests\Fixtures;

use Scraper\Scraper\Annotation\Scraper;

/**
 * @Scraper(path="add/child/path")
 */
final class TestChildRequest extends TestParentRequest
{
}

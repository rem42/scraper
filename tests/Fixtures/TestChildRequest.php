<?php declare(strict_types=1);

namespace Scraper\Scraper\Tests\Fixtures;

use Scraper\Scraper\Annotation\Scraper as ScraperAnnotation;
use Scraper\Scraper\Attribute\Scraper;

/**
 * @ScraperAnnotation(path="add/child/path")
 */
#[Scraper(path: 'add/child/path')]
final class TestChildRequest extends TestParentRequest
{
}

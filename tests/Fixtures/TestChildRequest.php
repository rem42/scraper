<?php declare(strict_types=1);

namespace Scraper\Scraper\Tests\Fixtures;

use Scraper\Scraper\Attribute\Scraper;

#[Scraper(path: 'add/child/path')]
final class TestChildRequest extends TestParentRequest
{
}

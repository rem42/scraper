<?php declare(strict_types=1);

namespace Scraper\Scraper\Tests\Fixtures;

use Scraper\Scraper\Api\AbstractApi;

final class TestApi extends AbstractApi
{
    public function execute(): bool
    {
        return true;
    }
}

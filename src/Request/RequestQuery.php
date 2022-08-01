<?php declare(strict_types=1);

namespace Scraper\Scraper\Request;

interface RequestQuery
{
    /**
     * @return array<int|string, int|string>
     */
    public function getQuery(): array;
}

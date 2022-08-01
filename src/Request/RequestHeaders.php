<?php declare(strict_types=1);

namespace Scraper\Scraper\Request;

interface RequestHeaders
{
    /**
     * @return array<string, string>
     */
    public function getHeaders(): array;
}

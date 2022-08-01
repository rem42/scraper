<?php declare(strict_types=1);

namespace Scraper\Scraper\Request;

interface RequestException
{
    public function isThrow(): bool;
}

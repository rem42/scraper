<?php declare(strict_types=1);

namespace Scraper\Scraper\Request;

interface RequestAuthBasic
{
    public function getAuthBasic(): string;
}

<?php declare(strict_types=1);

namespace Scraper\Scraper\Request;

interface RequestAuthBearer
{
    public function getBearer(): string;
}

<?php

namespace Scraper\Scraper\Request;

interface RequestQuery
{
    /**
     * @return array<int|string, string>
     */
    public function getQuery(): array;
}

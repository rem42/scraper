<?php

namespace Scraper\Scraper\Request;

interface RequestQuery
{
    /**
     * @return array<string, string>
     */
    public function getQuery(): array;
}

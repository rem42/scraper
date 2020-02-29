<?php

namespace Scraper\Scraper\Request;

interface RequestHeaders
{
    /**
     * @return array<string, string>
     */
    public function getHeaders(): array;
}

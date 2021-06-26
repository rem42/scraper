<?php

namespace Scraper\Scraper\Request;

interface RequestBodyJson
{
    /**
     * @return array<int|string, mixed>
     */
    public function getJson(): array;
}

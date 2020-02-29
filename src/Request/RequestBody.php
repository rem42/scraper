<?php

namespace Scraper\Scraper\Request;

interface RequestBody
{
    /**
     * @return array<string, string>|string|resource
     */
    public function getBody();
}

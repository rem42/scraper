<?php

namespace Scraper\Scraper\Request;

interface RequestBody
{
    /**
     * @return array<string, string>|resource|string
     */
    public function getBody();
}

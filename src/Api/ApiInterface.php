<?php

namespace Scraper\Scraper\Api;

interface ApiInterface
{
    /**
     * @return array<object>|bool|object|string
     */
    public function execute();
}

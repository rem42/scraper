<?php

namespace Scraper\Scraper\Api;

interface ApiInterface
{
    /**
     * @return object|array<object>|bool
     */
    public function execute();
}

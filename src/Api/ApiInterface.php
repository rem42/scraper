<?php

namespace Scraper\Scraper\Api;

interface ApiInterface
{
    /**
     * @return array<object>|bool|object
     */
    public function execute();
}

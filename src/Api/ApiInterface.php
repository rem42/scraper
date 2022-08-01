<?php declare(strict_types=1);

namespace Scraper\Scraper\Api;

interface ApiInterface
{
    /**
     * @return array<object>|bool|object|string
     */
    public function execute(): object|array|bool|string;
}

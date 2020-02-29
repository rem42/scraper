<?php

namespace Scraper\Scraper\Protocol;

use Psr\Http\Message\ResponseInterface;

interface IProtocol
{
    /**
     * @return ResponseInterface
     */
    public function execute();
}

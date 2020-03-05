<?php

namespace Scraper\Scraper\Mediator;

use GuzzleHttp\Psr7\Response;

interface IMediator
{
    /**
     * @param Response $response
     *
     * @return mixed
     */
    public function execute(Response $response);
}

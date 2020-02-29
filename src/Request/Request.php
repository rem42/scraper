<?php

namespace Scraper\Scraper\Request;

use GuzzleHttp\Psr7\Request as BaseRequest;

class Request extends BaseRequest
{
    public function __construct($method, array $headers = [], $body = null, $version = '1.1')
    {
        $uri = '';
        parent::__construct($method, $uri, $headers, $body, $version);
    }
}

<?php

namespace Scraper\Scraper\Adapter;

use GuzzleHttp\Psr7\Response;
use Scraper\Scraper\Annotation\Scraper;
use Scraper\Scraper\Request\Request;

abstract class Adapter implements AdapterInterface
{
    protected Request $request;
    protected \ReflectionClass $reflexionClass;
    protected Response $response;
    protected Scraper $scraper;

    public function __construct(Request $request, \ReflectionClass $reflexionClass, Response $response, Scraper $scraper)
    {
        $this->request        = $request;
        $this->reflexionClass = $reflexionClass;
        $this->response       = $response;
        $this->scraper        = $scraper;
    }
}

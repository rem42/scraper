<?php

namespace Scraper\Scraper\Adapter;

use Scraper\Scraper\Annotation\Scraper;
use Scraper\Scraper\Request\ScraperRequest;
use Symfony\Contracts\HttpClient\ResponseInterface;

abstract class Adapter implements AdapterInterface
{
    protected ScraperRequest $request;
    protected \ReflectionClass $reflexionClass;
    protected ResponseInterface $response;
    protected Scraper $scraper;

    public function __construct(ScraperRequest $request, \ReflectionClass $reflexionClass, ResponseInterface $response, Scraper $scraper)
    {
        $this->request        = $request;
        $this->reflexionClass = $reflexionClass;
        $this->response       = $response;
        $this->scraper        = $scraper;
    }
}

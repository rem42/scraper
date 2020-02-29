<?php

namespace Scraper\Scraper\Api;

use GuzzleHttp\Psr7\Response;
use Scraper\Scraper\Annotation\Scraper;
use Scraper\Scraper\Request\ScraperRequest;

abstract class AbstractApi implements ApiInterface
{
    protected Scraper $scraper;
    /** @var ScraperRequest */
    protected $request;
    protected Response $response;
    protected ?object $data;

    public function __construct(Scraper $scraper, ScraperRequest $request, Response $response, object $data = null)
    {
        $this->scraper  = $scraper;
        $this->request  = $request;
        $this->response = $response;
        $this->data     = $data;
    }
}

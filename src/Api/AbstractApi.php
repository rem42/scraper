<?php

namespace Scraper\Scraper\Api;

use Scraper\Scraper\Annotation\Scraper;
use Scraper\Scraper\Factory\SerializerFactory;
use Scraper\Scraper\Request\ScraperRequest;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\HttpClient\ResponseInterface;

abstract class AbstractApi implements ApiInterface
{
    protected Serializer $serializer;

    public function __construct(
        protected ScraperRequest $request,
        protected Scraper $scraper,
        protected ResponseInterface $response
    ) {
        $this->serializer = SerializerFactory::create();
    }
}

<?php

namespace Scraper\Scraper;

use Scraper\Scraper\Annotation\ExtractAnnotation;
use Scraper\Scraper\Annotation\Scraper;
use Scraper\Scraper\Request\ScraperRequest;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class Client
{
    private ScraperRequest $request;
    private Scraper $annotation;
    private HttpClientInterface $httpClient;

    public function __construct(ScraperRequest $request)
    {
        $this->request    = $request;
        $this->annotation = ExtractAnnotation::extract($this->request);
    }

    public function setHttpClient(HttpClientInterface $httpClient): self
    {
        $this->httpClient = $httpClient;

        return $this;
    }

    public function send(): bool
    {
        if (!$this->httpClient instanceof HttpClientInterface) {
            throw new \Exception('client not initialized');
        }

        $options = [
            'query' => [],
        ];

        $this->httpClient->request(
            $this->annotation->method,
            $this->annotation->url(),
            $options
        );

        return true;
    }
}

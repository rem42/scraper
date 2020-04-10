<?php

namespace Scraper\Scraper;

use Scraper\Scraper\Annotation\ExtractAnnotation;
use Scraper\Scraper\Annotation\Scraper;
use Scraper\Scraper\Api\AbstractApi;
use Scraper\Scraper\Request\RequestBearer;
use Scraper\Scraper\Request\RequestBody;
use Scraper\Scraper\Request\RequestHeaders;
use Scraper\Scraper\Request\ScraperRequest;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class Client
{
    private ScraperRequest $request;
    private Scraper $annotation;
    private HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function send(ScraperRequest $request): object
    {
        $this->request    = $request;
        $this->annotation = ExtractAnnotation::extract($this->request);

        if (!$this->httpClient instanceof HttpClientInterface) {
            throw new \Exception('client not initialized');
        }

        $options = [];

        if ($this->request instanceof RequestHeaders) {
            $options['headers'] = $this->request->getHeaders();
        }

        if ($this->request instanceof RequestBody) {
            $options['body'] = $this->request->getBody();
        }

        if ($this->request instanceof RequestBearer) {
            $options['auth_bearer'] = $this->request->getBearer();
        }

        try {
            $response = $this->httpClient->request(
                $this->annotation->method,
                $this->annotation->url(),
                $options
            );

            if ($response->getStatusCode() >= 300 || $response->getStatusCode() < 200) {
                throw new \Exception($response->getContent());
            }
        } catch (ServerExceptionInterface $e) {
            throw new \Exception('cannot get response from: ' . $this->annotation->url());
        }

        $apiReflectionClass = $this->getApiReflectionClass();

        /** @var AbstractApi $apiInstance */
        $apiInstance = $apiReflectionClass->newInstanceArgs([
            $this->request,
            $this->annotation,
            $response,
        ]);

        return $apiInstance->execute();
    }

    /**
     * @return \ReflectionClass<AbstractApi>
     */
    private function getApiReflectionClass(): \ReflectionClass
    {
        $requestReflectionClass = new \ReflectionClass($this->request);

        /** @var class-string<AbstractApi> $apiClass */
        $apiClass = str_replace('Request', 'Api', $requestReflectionClass->getName());

        if (!class_exists($apiClass)) {
            throw new \Exception('Api class for this request not exist: ' . $apiClass);
        }

        return new \ReflectionClass($apiClass);
    }
}

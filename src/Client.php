<?php

namespace Scraper\Scraper;

use Scraper\Scraper\Annotation\ExtractAnnotation;
use Scraper\Scraper\Api\AbstractApi;
use Scraper\Scraper\Request\RequestBearer;
use Scraper\Scraper\Request\RequestBody;
use Scraper\Scraper\Request\RequestHeaders;
use Scraper\Scraper\Request\RequestQuery;
use Scraper\Scraper\Request\ScraperRequest;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class Client
{
    private ScraperRequest $request;

    private HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @return object|array<object>|bool
     */
    public function send(ScraperRequest $request)
    {
        $this->request    = $request;
        $annotation       = ExtractAnnotation::extract($this->request);

        $options = [];

        if ($this->request instanceof RequestHeaders) {
            $options['headers'] = $this->request->getHeaders();
        }

        if ($this->request instanceof RequestQuery) {
            $options['query'] = $this->request->getQuery();
        }

        if ($this->request instanceof RequestBody) {
            $options['body'] = $this->request->getBody();
        }

        if ($this->request instanceof RequestBearer) {
            $options['auth_bearer'] = $this->request->getBearer();
        }

        try {
            $response = $this->httpClient->request(
                $annotation->method,
                $annotation->url(),
                $options
            );

            if ($response->getStatusCode() >= 300 || $response->getStatusCode() < 200) {
                throw new \Exception($response->getContent());
            }
        } catch (ServerExceptionInterface $serverExceptionInterface) {
            throw new \Exception('cannot get response from: ' . $annotation->url(), $serverExceptionInterface->getCode(), $serverExceptionInterface);
        }

        $apiReflectionClass = $this->getApiReflectionClass();

        /** @var AbstractApi $apiInstance */
        $apiInstance = $apiReflectionClass->newInstanceArgs([
            $this->request,
            $annotation,
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

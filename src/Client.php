<?php declare(strict_types=1);

namespace Scraper\Scraper;

use Scraper\Scraper\Api\AbstractApi;
use Scraper\Scraper\Attribute\ExtractAttribute;
use Scraper\Scraper\Exception\ScraperException;
use Scraper\Scraper\Request\RequestAuthBasic;
use Scraper\Scraper\Request\RequestAuthBearer;
use Scraper\Scraper\Request\RequestBody;
use Scraper\Scraper\Request\RequestBodyJson;
use Scraper\Scraper\Request\RequestException;
use Scraper\Scraper\Request\RequestHeaders;
use Scraper\Scraper\Request\RequestQuery;
use Scraper\Scraper\Request\ScraperRequest;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class Client
{
    private ScraperRequest $request;

    public function __construct(
        protected HttpClientInterface $httpClient
    ) {}

    /**
     * @return array<object>|bool|object|string
     */
    public function send(ScraperRequest $request)
    {
        $this->request = $request;
        $attribute = ExtractAttribute::extract($this->request);
        $options = $this->buildOptions();

        $throw = $this->isThrow();

        try {
            $response = $this->httpClient->request(
                $attribute->getMethod(),
                $attribute->url(),
                $options
            );

            if ($throw && ($response->getStatusCode() >= 300 || $response->getStatusCode() < 200)) {
                throw new ScraperException($response->getContent(false));
            }
        } catch (\Throwable $throwable) {
            throw new ScraperException('cannot get response from: ' . $attribute->url(), \is_int($throwable->getCode()) ? $throwable->getCode() : 0, $throwable);
        }

        $apiReflectionClass = $this->getApiReflectionClass();

        /** @var AbstractApi $apiInstance */
        $apiInstance = $apiReflectionClass->newInstanceArgs([
            $this->request,
            $attribute,
            $response,
        ]);

        return $apiInstance->execute();
    }

    /**
     * @return \ReflectionClass<AbstractApi>
     */
    private function getApiReflectionClass(): \ReflectionClass
    {
        $class = new \ReflectionClass($this->request);

        /** @var class-string<AbstractApi> $apiClass */
        $apiClass = str_replace('Request', 'Api', $class->getName());

        if (!class_exists($apiClass)) {
            throw new ScraperException('Api class for this request not exist: ' . $apiClass);
        }

        return new \ReflectionClass($apiClass);
    }

    /**
     * @return array<string, array<int|string, mixed>|resource|string>
     */
    private function buildOptions(): array
    {
        $options = [];

        if ($this->request instanceof RequestAuthBearer) {
            $options['auth_bearer'] = $this->request->getBearer();
        }

        if ($this->request instanceof RequestAuthBasic && false !== $this->request->isAuthBasic()) {
            $options['auth_basic'] = $this->request->getAuthBasic();
        }

        if ($this->request instanceof RequestHeaders) {
            $options['headers'] = $this->request->getHeaders();
        }

        if ($this->request instanceof RequestQuery) {
            $options['query'] = $this->request->getQuery();
        }

        if ($this->request instanceof RequestBody) {
            $options['body'] = $this->request->getBody();
        }

        if ($this->request instanceof RequestBodyJson) {
            $options['json'] = $this->request->getJson();
        }
        return $options;
    }

    private function isThrow(): bool
    {
        $throw = true;

        if ($this->request instanceof RequestException) {
            $throw = $this->request->isThrow();
        }
        return $throw;
    }
}

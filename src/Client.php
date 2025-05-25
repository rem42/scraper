<?php

namespace Scraper\Scraper;

use Scraper\Scraper\Annotation\ExtractAnnotation;
use Scraper\Scraper\Api\AbstractApi;
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

    private HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @return array<object>|bool|object|string
     */
    public function send(ScraperRequest $request)
    {
        $this->request = $request;
        $annotation    = ExtractAnnotation::extract($this->request);
        $options       = $this->buildOptions();

        return $this->executeApiCall($annotation, $options);
    }

    /**
     * @param ExtractAnnotation $annotation
     * @param array<string, array<int|string,mixed>|resource|string> $options
     * @return array<object>|bool|object|string
     */
    private function executeApiCall(ExtractAnnotation $annotation, array $options)
    {
        $throw = $this->isThrow();

        try {
            $response = $this->httpClient->request(
                $annotation->method,
                $annotation->url(),
                $options
            );

            if ($throw && ($response->getStatusCode() >= 300 || $response->getStatusCode() < 200)) {
                throw new ScraperException($response->getContent(false));
            }
        } catch (\Throwable $throwable) {
            throw new ScraperException('cannot get response from: ' . $annotation->url(), \is_int($throwable->getCode()) ? $throwable->getCode() : 0, $throwable);
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
        $class = new \ReflectionClass($this->request);

        /** @var class-string<AbstractApi> $apiClass */
        $apiClass = str_replace('Request', 'Api', $class->getName());

        if (!class_exists($apiClass)) {
            throw new ScraperException('Api class for this request not exist: ' . $apiClass);
        }

        return new \ReflectionClass($apiClass);
    }

use App\Request\RequestOptionProvider;

    /**
     * @return array<string, array<int|string,mixed>|resource|string>
     */
    private function buildOptions(): array
    {
        $options = [];

        // Define the list of RequestOptionProvider implementers
        $optionProviders = [
            RequestAuthBasic::class,
            RequestAuthBearer::class,
            RequestBody::class,
            RequestBodyJson::class,
            RequestHeaders::class,
            RequestQuery::class,
        ];

        foreach ($optionProviders as $providerClass) {
            if ($this->request instanceof $providerClass) {
                // Ensure $this->request is treated as RequestOptionProvider
                if ($this->request instanceof RequestOptionProvider) {
                    $options = array_merge($options, $this->request->getOptions());
                }
            }
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

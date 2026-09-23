<?php

declare(strict_types=1);

namespace Scraper\Scraper;

use Scraper\Scraper\Api\AbstractApi;
use Scraper\Scraper\Attribute\ExtractAttribute;
use Scraper\Scraper\Builder\RequestOptionBuilder;
use Scraper\Scraper\Exception\ScraperException;
use Scraper\Scraper\Exception\ScraperHttpException;
use Scraper\Scraper\Exception\ScraperNotFoundException;
use Scraper\Scraper\Factory\ApiFactory;
use Scraper\Scraper\Request\RequestException;
use Scraper\Scraper\Request\ScraperRequest;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class Client
{
    private ScraperRequest $request;

    public function __construct(
        private readonly HttpClientInterface $httpClient,
    ) {}

    /**
     * @return array<object>|bool|object|string
     */
    public function send(ScraperRequest $request): object|bool|string|array
    {
        $this->request = $request;
        $config = ExtractAttribute::extract($this->request);
        $options = RequestOptionBuilder::build($this->request);

        $throw = $this->isThrow();

        /** @var ResponseInterface|null $response */
        $response = null;

        try {
            $response = $this->httpClient->request(
                $config->getMethod(),
                $config->url(),
                $options
            );

            if (
                $throw
                && ($response->getStatusCode() >= 300 || $response->getStatusCode() < 200)
            ) {
                throw new ScraperHttpException(sprintf('HTTP %d returned for %s: %s', $response->getStatusCode(), $config->url(), $response->getContent(false)), $response->getStatusCode(), $config->url(), $response->getContent(false));
            }
        } catch (\Throwable $throwable) {
            if ($response instanceof ResponseInterface && 404 === $response->getStatusCode()) {
                throw new ScraperNotFoundException($response->getContent(false));
            }

            throw new ScraperException('cannot get response from: ' . $config->url(), \is_int($throwable->getCode()) ? $throwable->getCode() : 0, $throwable);
        }

        $apiReflectionClass = ApiFactory::getReflectionClass($this->request);

        /** @var AbstractApi $apiInstance */
        $apiInstance = $apiReflectionClass->newInstanceArgs([
            $this->request,
            $config,
            $response,
        ]);

        return $apiInstance->execute();
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

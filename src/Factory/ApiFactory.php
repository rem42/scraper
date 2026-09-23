<?php

declare(strict_types=1);

namespace Scraper\Scraper\Factory;

use Scraper\Scraper\Api\AbstractApi;
use Scraper\Scraper\Exception\ScraperConfigurationException;
use Scraper\Scraper\Request\ScraperRequest;

final class ApiFactory
{
    /**
     * @return \ReflectionClass<AbstractApi>
     */
    public static function getReflectionClass(ScraperRequest $request): \ReflectionClass
    {
        $class = new \ReflectionClass($request);

        /** @var class-string<AbstractApi> $apiClass */
        $apiClass = str_replace('Request', 'Api', $class->getName());

        if (!class_exists($apiClass) || !is_subclass_of($apiClass, AbstractApi::class)) {
            throw new ScraperConfigurationException('Api class for this request not exist or is invalid: ' . $apiClass);
        }

        return new \ReflectionClass($apiClass);
    }
}

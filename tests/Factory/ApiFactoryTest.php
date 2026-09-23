<?php

declare(strict_types=1);

namespace Scraper\Scraper\Tests\Factory;

use PHPUnit\Framework\TestCase;
use Scraper\Scraper\Api\AbstractApi;
use Scraper\Scraper\Exception\ScraperConfigurationException;
use Scraper\Scraper\Factory\ApiFactory;
use Scraper\Scraper\Tests\Fixtures\TestApiAuth;
use Scraper\Scraper\Tests\Fixtures\TestRequestAuth;
use Scraper\Scraper\Tests\Fixtures\TestWithoutApiFileRequest;

/**
 * @internal
 */
final class ApiFactoryTest extends TestCase
{
    public function testGetReflectionClass(): void
    {
        $request = new TestRequestAuth();
        $reflectionClass = ApiFactory::getReflectionClass($request);

        $this->assertEquals(TestApiAuth::class, $reflectionClass->name);
        $this->assertEquals(AbstractApi::class, $reflectionClass->getParentClass()->name);
    }

    public function testGetReflectionClassWithoutApiFile(): void
    {
        $request = new TestWithoutApiFileRequest();
        $this->expectException(ScraperConfigurationException::class);

        ApiFactory::getReflectionClass($request);
    }
}

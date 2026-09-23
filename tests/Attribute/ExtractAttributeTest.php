<?php

declare(strict_types=1);

namespace Scraper\Scraper\Tests\Attribute;

use PHPUnit\Framework\TestCase;
use Scraper\Scraper\Attribute\ExtractAttribute;
use Scraper\Scraper\Exception\ClassNotInitializedException;
use Scraper\Scraper\Tests\Fixtures\TestChildChangePathRequest;
use Scraper\Scraper\Tests\Fixtures\TestChildRequest;
use Scraper\Scraper\Tests\Fixtures\TestRequestAuth;
use Scraper\Scraper\Tests\Fixtures\TestWithAnnotationParametersRequest;
use Scraper\Scraper\Tests\Fixtures\TestWithoutAnnotationRequest;

/**
 * @internal
 */
class ExtractAttributeTest extends TestCase
{
    public function testExtractRequest(): void
    {
        $request = new TestRequestAuth();

        $config = ExtractAttribute::extract($request);

        $this->assertEquals('GET', $config->method->value);
        $this->assertEquals('HTTPS', $config->scheme->value);
        $this->assertEquals('host-test.api', $config->host);
        $this->assertEquals('path/to/endpoint', $config->path);
    }

    public function testExtractRequestWithParameters(): void
    {
        $request = new TestWithAnnotationParametersRequest();
        $request
            ->setEndpoint('my-endpoint')
            ->setNdd('fr')
        ;

        $config = ExtractAttribute::extract($request);

        $this->assertEquals('HTTPS', $config->scheme->value);
        $this->assertEquals('host-test.fr', $config->host);
        $this->assertEquals('path/to/my-endpoint', $config->path);
        $this->assertEquals('GET', $config->method->value);
    }

    public function testExtractRequestWithoutAnnotation(): void
    {
        $request = new TestWithoutAnnotationRequest();

        $this->expectException(ClassNotInitializedException::class);

        ExtractAttribute::extract($request);
    }

    public function testExtractRequestWithParentRequest(): void
    {
        $request = new TestChildRequest();

        $config = ExtractAttribute::extract($request);

        $this->assertEquals('path/to/endpoint/add/child/path', $config->path);
    }

    public function testExtractRequestWithParentAndChangePathRequest(): void
    {
        $request = new TestChildChangePathRequest();

        $config = ExtractAttribute::extract($request);

        $this->assertEquals('/add/child/path', $config->path);
    }

    public function testDisableEnableSSL(): void
    {
        $request = new TestChildRequest();
        $request->disableSSL();

        $config = ExtractAttribute::extract($request);

        $this->assertEquals('HTTP', $config->scheme->value);
        $request->enableSSL();

        $config = ExtractAttribute::extract($request);

        $this->assertEquals('HTTPS', $config->scheme->value);
    }
}

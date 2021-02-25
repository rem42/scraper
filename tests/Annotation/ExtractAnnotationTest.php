<?php

namespace Scraper\Scraper\Tests\Annotation;

use PHPUnit\Framework\TestCase;
use Scraper\Scraper\Annotation\ExtractAnnotation;
use Scraper\Scraper\Exception\ClassNotInitializedException;
use Scraper\Scraper\Tests\Fixtures\TestChildChangePathRequest;
use Scraper\Scraper\Tests\Fixtures\TestChildRequest;
use Scraper\Scraper\Tests\Fixtures\TestRequestAuth;
use Scraper\Scraper\Tests\Fixtures\TestWithAnnotationParametersRequest;
use Scraper\Scraper\Tests\Fixtures\TestWithoutAnnotationRequest;

/**
 * @internal
 */
final class ExtractAnnotationTest extends TestCase
{
    public function testExtractRequest(): void
    {
        $request = new TestRequestAuth();

        $scraper = ExtractAnnotation::extract($request);

        $this->assertEquals('HTTPS', $scraper->scheme);
        $this->assertEquals('host-test.api', $scraper->host);
        $this->assertEquals('path/to/endpoint', $scraper->path);
        $this->assertEquals('GET', $scraper->method);
    }

    public function testExtractRequestWithParameters(): void
    {
        $request = new TestWithAnnotationParametersRequest();
        $request
            ->setEndpoint('my-endpoint')
            ->setNdd('fr')
        ;

        $scraper = ExtractAnnotation::extract($request);

        $this->assertEquals('HTTPS', $scraper->scheme);
        $this->assertEquals('host-test.fr', $scraper->host);
        $this->assertEquals('path/to/my-endpoint', $scraper->path);
        $this->assertEquals('GET', $scraper->method);
    }

    public function testExtractRequestWithoutAnnotation(): void
    {
        $request = new TestWithoutAnnotationRequest();

        $this->expectException(ClassNotInitializedException::class);

        ExtractAnnotation::extract($request);
    }

    public function testExtractRequestWithParentRequest(): void
    {
        $request = new TestChildRequest();

        $scraper = ExtractAnnotation::extract($request);

        $this->assertEquals('path/to/endpoint/add/child/path', $scraper->path);
    }

    public function testExtractRequestWithParentAndChangePathRequest(): void
    {
        $request = new TestChildChangePathRequest();

        $scraper = ExtractAnnotation::extract($request);

        $this->assertEquals('/add/child/path', $scraper->path);
    }

    public function testDisableEnableSSL(): void
    {
        $request = new TestChildRequest();
        $request->disableSSL();

        $scraper = ExtractAnnotation::extract($request);

        $this->assertEquals('HTTP', $scraper->scheme);
        $request->enableSSL();

        $scraper = ExtractAnnotation::extract($request);

        $this->assertEquals('HTTPS', $scraper->scheme);
    }
}

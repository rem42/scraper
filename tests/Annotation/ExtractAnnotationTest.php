<?php

namespace Scraper\Scraper\Tests\Annotation;

use PHPUnit\Framework\TestCase;
use Scraper\Scraper\Annotation\ExtractAnnotation;
use Scraper\Scraper\Exception\ClassNotInitializedException;
use Scraper\Scraper\Tests\Fixtures\TestRequest;
use Scraper\Scraper\Tests\Fixtures\TestWithAnnotationParametersRequest;
use Scraper\Scraper\Tests\Fixtures\TestWithoutAnnotationRequest;

final class ExtractAnnotationTest extends TestCase
{
    public function testExtractRequest(): void
    {
        $request = new TestRequest();

        $scraper = ExtractAnnotation::extract($request);

        $this->assertEquals('HTTPS', $scraper->scheme);
        $this->assertEquals('host-test.api', $scraper->host);
        $this->assertEquals('path/to/endpoint', $scraper->path);
        $this->assertEquals('GET', $scraper->method);
        $this->assertEquals(443, $scraper->port);
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
        $this->assertEquals(443, $scraper->port);
    }

    public function testExtractRequestWithoutAnnotation(): void
    {
        $request = new TestWithoutAnnotationRequest();

        $this->expectException(ClassNotInitializedException::class);

        ExtractAnnotation::extract($request);
    }
}
<?php declare(strict_types=1);

namespace Scraper\Scraper\Tests\Attribute;

use PHPUnit\Framework\TestCase;
use Scraper\Scraper\Attribute\ExtractAttribute;
use Scraper\Scraper\Attribute\Method;
use Scraper\Scraper\Attribute\Scheme;
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

        $scraper = ExtractAttribute::extract($request);

        $this->assertInstanceOf(Method::class, $scraper->method);
        $this->assertEquals('GET', $scraper->method->value);
        $this->assertInstanceOf(Scheme::class, $scraper->scheme);
        $this->assertEquals('HTTPS', $scraper->scheme->value);
        $this->assertEquals('host-test.api', $scraper->host);
        $this->assertEquals('path/to/endpoint', $scraper->path);
    }

    public function testExtractRequestWithParameters(): void
    {
        $request = new TestWithAnnotationParametersRequest();
        $request
            ->setEndpoint('my-endpoint')
            ->setNdd('fr')
        ;

        $scraper = ExtractAttribute::extract($request);

        $this->assertEquals('HTTPS', $scraper->scheme->value);
        $this->assertEquals('host-test.fr', $scraper->host);
        $this->assertEquals('path/to/my-endpoint', $scraper->path);
        $this->assertEquals('GET', $scraper->method->value);
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

        $scraper = ExtractAttribute::extract($request);

        $this->assertEquals('path/to/endpoint/add/child/path', $scraper->path);
    }

    public function testExtractRequestWithParentAndChangePathRequest(): void
    {
        $request = new TestChildChangePathRequest();

        $scraper = ExtractAttribute::extract($request);

        $this->assertEquals('/add/child/path', $scraper->path);
    }

    public function testDisableEnableSSL(): void
    {
        $request = new TestChildRequest();
        $request->disableSSL();

        $scraper = ExtractAttribute::extract($request);

        $this->assertEquals('HTTP', $scraper->scheme->value);
        $request->enableSSL();

        $scraper = ExtractAttribute::extract($request);

        $this->assertEquals('HTTPS', $scraper->scheme->value);
    }
}

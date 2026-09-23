<?php

declare(strict_types=1);

namespace Scraper\Scraper\Tests\Builder;

use PHPUnit\Framework\TestCase;
use Scraper\Scraper\Builder\RequestOptionBuilder;
use Scraper\Scraper\Tests\Fixtures\TestRequestAuth;

/**
 * @internal
 */
final class RequestOptionBuilderTest extends TestCase
{
    public function testBuildOptions(): void
    {
        $request = new TestRequestAuth();

        $options = RequestOptionBuilder::build($request);

        $this->assertIsArray($options);

        $this->assertArrayHasKey('headers', $options);
        $this->assertIsArray($options['headers']);
        $this->assertArrayHasKey('custom-header', $options['headers']);
        $this->assertEquals('header', $options['headers']['custom-header']);

        $this->assertArrayHasKey('query', $options);
        $this->assertIsArray($options['query']);
        $this->assertArrayHasKey('custom-query', $options['query']);
        $this->assertEquals('query', $options['query']['custom-query']);

        $this->assertArrayHasKey('body', $options);
        $this->assertEquals('body', $options['body']);

        $this->assertArrayHasKey('auth_bearer', $options);
        $this->assertEquals('bearerToken', $options['auth_bearer']);
    }
}

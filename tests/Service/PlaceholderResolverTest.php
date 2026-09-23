<?php

declare(strict_types=1);

namespace Scraper\Scraper\Tests\Service;

use PHPUnit\Framework\TestCase;
use Scraper\Scraper\Service\PlaceholderResolver;
use Scraper\Scraper\Tests\Fixtures\TestWithAnnotationParametersRequest;

/**
 * @internal
 */
final class PlaceholderResolverTest extends TestCase
{
    public function testResolve(): void
    {
        $request = new TestWithAnnotationParametersRequest();
        $request
            ->setEndpoint('my-endpoint')
            ->setNdd('fr')
        ;

        $resolved = PlaceholderResolver::resolve('host-test.{ndd}', $request);
        $this->assertEquals('host-test.fr', $resolved);

        $resolved = PlaceholderResolver::resolve('path/to/{endpoint}', $request);
        $this->assertEquals('path/to/my-endpoint', $resolved);
    }
}

<?php

declare(strict_types=1);

namespace Scraper\Scraper\Tests;

use PHPUnit\Framework\TestCase;
use Scraper\Scraper\Client;
use Scraper\Scraper\Tests\Fixtures\TestRequestAuth;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * @internal
 */
final class ClientTest extends TestCase
{
    public function testSend(): void
    {
        $responseInterface = $this->createMock(ResponseInterface::class);
        $responseInterface
            ->method('getStatusCode')->willReturn(200)
        ;

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient
            ->method('request')->willReturn($responseInterface)
        ;
        $client = new Client($httpClient);

        $request = new TestRequestAuth();

        $result = $client->send($request);

        $this->assertTrue($result);
    }

    public function testResponseWithException(): void
    {
        $exception = $this->createMock(ServerExceptionInterface::class);
        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient
            ->method('request')->willThrowException($exception)
        ;
        $client = new Client($httpClient);

        $request = new TestRequestAuth();
        $this->expectException(\Exception::class);

        $client->send($request);
    }

    public function testSendWrongStatusCode(): void
    {
        $responseInterface = $this->createMock(ResponseInterface::class);
        $responseInterface
            ->method('getStatusCode')->willReturn(404)
        ;

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient
            ->method('request')->willReturn($responseInterface)
        ;

        $client = new Client($httpClient);

        $request = new TestRequestAuth();
        $this->expectException(\Exception::class);

        $client->send($request);
    }
}

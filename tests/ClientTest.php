<?php

namespace Scraper\Scraper\Tests;

use PHPUnit\Framework\TestCase;
use Scraper\Scraper\Api\AbstractApi;
use Scraper\Scraper\Client;
use Scraper\Scraper\Tests\Fixtures\HttpClientTest;
use Scraper\Scraper\Tests\Fixtures\TestApi;
use Scraper\Scraper\Tests\Fixtures\TestRequest;
use Scraper\Scraper\Tests\Fixtures\TestWithoutApiFileRequest;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\ResponseStreamInterface;

final class ClientTest extends TestCase
{
    public function testSend(): void
    {
        $responseInterface = $this->createMock(ResponseInterface::class);
        $responseInterface
            ->method('getStatusCode')->willReturn(200);

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient
            ->method('request')->willReturn($responseInterface)
        ;
        $client = new Client($httpClient);

        $request = new TestRequest();

        $result = $client->send($request);

        $this->assertEquals(true, $result);
    }

    public function testResponseWithException(): void
    {
        $exception  = $this->createMock(ServerExceptionInterface::class);
        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient
            ->method('request')->willThrowException($exception)
        ;
        $client = new Client($httpClient);

        $request = new TestRequest();
        //$this->expectException(ServerExceptionInterface::class);
        $this->expectException(\Exception::class);

        $result = $client->send($request);
    }

    public function testWithAllOptions(): void
    {
        $responseInterface = $this->createMock(ResponseInterface::class);
        $responseStream    = $this->createMock(ResponseStreamInterface::class);
        $responseInterface
            ->method('getStatusCode')->willReturn(200);

        $httpClient = new HttpClientTest($responseInterface, $responseStream);

        $client = new Client($httpClient);

        $request = new TestRequest();

        $client->send($request);

        $options = $httpClient->getOptions();

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

    public function testSendWrongStatusCode(): void
    {
        $responseInterface = $this->createMock(ResponseInterface::class);
        $responseInterface
            ->method('getStatusCode')->willReturn(404);

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient
            ->method('request')->willReturn($responseInterface);

        $client = new Client($httpClient);

        $request = new TestRequest();
        $this->expectException(\Exception::class);

        $client->send($request);
    }

    public function testWithoutApiFile(): void
    {
        $responseInterface = $this->createMock(ResponseInterface::class);
        $responseInterface
            ->method('getStatusCode')->willReturn(200);

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient
            ->method('request')->willReturn($responseInterface);

        $client = new Client($httpClient);

        $request = new TestWithoutApiFileRequest();
        $this->expectException(\Exception::class);

        $client->send($request);
    }

    public function testPrivateSend(): void
    {
        $responseInterface = $this->createMock(ResponseInterface::class);
        $responseInterface
            ->method('getStatusCode')->willReturn(200);

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient
            ->method('request')->willReturn($responseInterface);

        $client = new Client($httpClient);

        $reflection = new \ReflectionClass(get_class($client));
        $method     = $reflection->getMethod('getApiReflectionClass');
        $method->setAccessible(true);

        $request = new TestRequest();
        $client->send($request);

        /** @var \ReflectionClass $reflectionClass */
        $reflectionClass = $method->invokeArgs($client, []);

        $this->assertEquals(TestApi::class, $reflectionClass->name);
        $this->assertEquals(AbstractApi::class, $reflectionClass->getParentClass()->name);
    }
}

<?php declare(strict_types=1);

namespace Scraper\Scraper\Tests;

use PHPUnit\Framework\TestCase;
use Scraper\Scraper\Api\AbstractApi;
use Scraper\Scraper\Client;
use Scraper\Scraper\Tests\Fixtures\HttpClientTest;
use Scraper\Scraper\Tests\Fixtures\TestApiAuth;
use Scraper\Scraper\Tests\Fixtures\TestRequestAuth;
use Scraper\Scraper\Tests\Fixtures\TestWithoutApiFileRequest;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\ResponseStreamInterface;

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

    public function testWithAllOptions(): void
    {
        $responseInterface = $this->createMock(ResponseInterface::class);
        $responseStream = $this->createMock(ResponseStreamInterface::class);
        $responseInterface
            ->method('getStatusCode')->willReturn(200)
        ;

        $httpClient = new HttpClientTest($responseInterface, $responseStream);

        $client = new Client($httpClient);

        $request = new TestRequestAuth();

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

    public function testWithoutApiFile(): void
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

        $request = new TestWithoutApiFileRequest();
        $this->expectException(\Exception::class);

        $client->send($request);
    }

    public function testPrivateSend(): void
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

        $reflection = new \ReflectionClass($client::class);
        $method = $reflection->getMethod('getApiReflectionClass');
        $method->setAccessible(true);

        $request = new TestRequestAuth();
        $client->send($request);

        /** @var \ReflectionClass $reflectionClass */
        $reflectionClass = $method->invokeArgs($client, []);

        $this->assertEquals(TestApiAuth::class, $reflectionClass->name);
        $this->assertEquals(AbstractApi::class, $reflectionClass->getParentClass()->name);
    }
}

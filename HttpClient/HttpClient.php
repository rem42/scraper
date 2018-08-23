<?php

namespace Scraper\Scraper\HttpClient;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

class HttpClient implements HttpClientInterface
{
	/**
	 * @var ClientInterface
	 */
	public $client;

	protected $options;

	protected $headers = [];

	private $lastResponse;
	private $lastRequest;

	/**
	 * HttpClient constructor.
	 *
	 * @param array                $options
	 * @param ClientInterface|NULL $client
	 */
	public function __construct(array $options = [], ClientInterface $client = null)
	{
		$client       = $client ?: new Client();
		$this->client = $client;

		$this->headers = [
			"headers" => [],
			"query" => [],
			"form_params" => [],
		];
	}

	/**
	 * @param string      $tokenOrLogin
	 * @param null|string $password
	 * @param null|string $authMethod
	 */
	public function authenticate($tokenOrLogin, $password, $authMethod)
	{
		// TODO: Implement authenticate() method.
	}

	/**
	 * @param       $httpMethod
	 * @param       $path
	 * @param null  $body
	 * @param array $headers
	 *
	 * @return Request
	 */
	public function createRequest($httpMethod, $path, $body = null, array $headers = [])
	{
		return new Request(
			$httpMethod,
			$path,
			array_merge($this->headers, $headers),
			$body
		);
	}

	/**
	 * @param string $path
	 * @param array  $parameters
	 * @param array  $headers
	 *
	 * @return Response|mixed|\Psr\Http\Message\ResponseInterface
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 */
	public function get($path, array $parameters = [], array $headers = [])
	{
		$this->setHeaders($headers);
		$this->setQuery($parameters);
		return $this->client->request('GET', $path, $this->headers);
	}

	/**
	 * @return Request
	 */
	public function getLastRequest()
	{
		return $this->lastRequest;
	}

	/**
	 * @return Response
	 */
	public function getLastResponse()
	{
		return $this->lastResponse;
	}

	/**
	 * @param string $path
	 * @param array  $parameters
	 * @param array  $headers
	 * @param null   $body
	 *
	 * @return Response|mixed|\Psr\Http\Message\ResponseInterface
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 */
	public function post($path, array $parameters = [], array $headers = [], $body = null)
	{
		$this->setQuery($parameters);
		$this->setHeaders($headers);
		$this->setFormParams($body);

		return $this->client->request('POST', $path, $this->headers);
	}

	/**
	 * @param string $path
	 * @param mixed  $body
	 * @param string $httpMethod
	 * @param array  $headers
	 * @param array  $options
	 *
	 * @return Response|mixed|\Psr\Http\Message\ResponseInterface
	 * @throws \ErrorException
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 */
	public function request($path, $body, $httpMethod = 'GET', array $headers = [], array $options = [])
	{
		$request = $this->createRequest($httpMethod, $path, $body, $headers);

		try {
			$response = $this->client->send($request, $options);
		} catch (\LogicException $e) {
			throw new \ErrorException($e->getMessage(), $e->getCode(), $e);
		} catch (\RuntimeException $e) {
			throw new \RuntimeException($e->getMessage(), $e->getCode(), $e);
		}

		$this->lastRequest  = $request;
		$this->lastResponse = $response;

		return $response;
	}

	/**
	 * @param array $body
	 */
	public function setFormParams(array $body = [])
	{
		if ($body) {
			$this->headers["form_params"] = array_merge($this->headers["form_params"], $body);
		}
	}

	/**
	 * @param array $headers
	 */
	public function setHeaders(array $headers = [])
	{
		if ($headers) {
			$this->headers["headers"] = array_merge($this->headers["headers"], $headers);
		}
	}

	/**
	 * @param string $name
	 * @param mixed  $value
	 */
	public function setOption($name, $value)
	{
		$this->options[$name] = $value;
	}

	/**
	 * @param array $query
	 */
	public function setQuery(array $query = [])
	{
		if ($query) {
			$this->headers["query"] = $query;
		}
	}
}

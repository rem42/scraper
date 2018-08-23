<?php

namespace Scraper\Scraper\HttpClient;

use GuzzleHttp\Psr7\Response;

interface HttpClientInterface
{
	/**
	 * Authenticate a user for all next requests.
	 *
	 * @param string      $tokenOrLogin GitHub private token/username/client ID
	 * @param null|string $password     GitHub password/secret (optionally can contain $authMethod)
	 * @param null|string $authMethod   One of the AUTH_* class constants
	 *
	 * @throws \InvalidArgumentException If no authentication method was given
	 */
	public function authenticate($tokenOrLogin, $password, $authMethod);

	/**
	 * Send a GET request.
	 *
	 * @param string $path       Request path
	 * @param array  $parameters GET Parameters
	 * @param array  $headers    Reconfigure the request headers for this call only
	 *
	 * @return Response
	 */
	public function get($path, array $parameters = [], array $headers = []);

	/**
	 * Send a POST request.
	 *
	 * @param string $path    Request path
	 * @param mixed  $body    Request body
	 * @param array  $headers Reconfigure the request headers for this call only
	 *
	 * @return Response
	 */
	public function post($path, array $parameters = [], array $headers = [], $body = null);

	/**
	 * Send a request to the server, receive a response,
	 * decode the response and returns an associative array.
	 *
	 * @param string $path       Request path
	 * @param mixed  $body       Request body
	 * @param string $httpMethod HTTP method to use
	 * @param array  $headers    Request headers
	 *
	 * @return Response
	 */
	public function request($path, $body, $httpMethod = 'GET', array $headers = [], array $options = []);

	/**
	 * Set HTTP headers.
	 *
	 * @param array $headers
	 */
	public function setFormParams(array $body = []);

	/**
	 * Set HTTP headers.
	 *
	 * @param array $headers
	 */
	public function setHeaders(array $headers = []);

	/**
	 * Change an option value.
	 *
	 * @param string $name  The option name
	 * @param mixed  $value The value
	 *
	 * @throws \InvalidArgumentException
	 */
	public function setOption($name, $value);

	/**
	 * Set HTTP headers.
	 *
	 * @param array $headers
	 */
	public function setQuery(array $query = []);
}

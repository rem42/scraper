<?php

namespace Scraper\Scraper\Protocol;

use GuzzleHttp\Psr7\Response;
use Scraper\Scraper\Annotation\UrlAnnotation;

class Curl extends Protocol
{
	/**
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	public function execute()
	{
		try {
			$function = strtolower($this->urlAnnotation->method);
			/** @var Response $response */
			return $this->$function();
		} catch (\Exception $e) {
			echo 'Exception: ', $e->getMessage(), "\n";
		}
	}

	/**
	 * @return Response|mixed|\Psr\Http\Message\ResponseInterface
	 * @throws \ErrorException
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 */
	protected function get()
	{
		$client = $this->getHttpClient();
		return $client
			->request($this->makeUrl(), null, UrlAnnotation::$METHOD_GET, $this->request->getHeaders());
	}

	/**
	 * @return Response|mixed|\Psr\Http\Message\ResponseInterface
	 * @throws \ErrorException
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 */
	protected function post()
	{
		$client = $this->getHttpClient();
		return $client
			->request($this->makeUrl(), $this->request->getBody(), UrlAnnotation::$METHOD_POST, $this->request->getHeaders());
	}

	/**
	 * @return Response|mixed|\Psr\Http\Message\ResponseInterface
	 * @throws \ErrorException
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 */
	protected function put()
	{
		$client = $this->getHttpClient();
		return $client
			->request($this->makeUrl(), $this->request->getBody(), UrlAnnotation::$METHOD_PUT, $this->request->getHeaders());
	}
}

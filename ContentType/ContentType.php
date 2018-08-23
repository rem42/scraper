<?php

namespace Scraper\Scraper\ContentType;

use GuzzleHttp\Psr7\Response;
use Scraper\Scraper\Annotation\UrlAnnotation;
use Scraper\Scraper\Cache\ICache;
use Scraper\Scraper\Request\Request;

abstract class ContentType implements IContentType
{
	/**
	 * @var Request
	 */
	protected $request;
	/**
	 * @var \ReflectionClass
	 */
	protected $reflexionClass;
	/**
	 * @var Response
	 */
	protected $response;
	/**
	 * @var UrlAnnotation
	 */
	protected $urlAnnotation;
	/**
	 * @var ICache
	 */
	protected $cache;

	/**
	 * ContentType constructor.
	 *
	 * @param \ReflectionClass $reflexionClass
	 * @param Response         $response
	 * @param UrlAnnotation    $urlAnnotation
	 */
	public function __construct(\ReflectionClass $reflexionClass, Request $request, Response $response, UrlAnnotation $urlAnnotation, ICache $cache = null)
	{
		$this->reflexionClass = $reflexionClass;
		$this->request        = $request;
		$this->response       = $response;
		$this->urlAnnotation  = $urlAnnotation;
		$this->cache          = $cache;
	}

	public function setCache($data)
	{
		if ($this->cache instanceof ICache) {
			$this->cache->write($data);
		}
	}
}

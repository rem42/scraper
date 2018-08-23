<?php

namespace Scraper\Scraper\Cache;

use Scraper\Scraper\Request\Request;

abstract class Cache implements ICache
{
	/**
	 * @var Request
	 */
	protected $request;
	/**
	 * @var \ReflectionClass
	 */
	protected $reflectionClass;

	/**
	 * Cache constructor.
	 *
	 * @param Request          $request
	 * @param \ReflectionClass $reflectionClass
	 */
	public function __construct(Request $request, \ReflectionClass $reflectionClass)
	{
		$request->removeFioritures();
		$this->request         = $request;
		$this->reflectionClass = $reflectionClass;
	}
}

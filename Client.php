<?php

namespace Scraper\Scraper;

use GuzzleHttp\Psr7\Response;
use Scraper\Scraper\Annotation\Reader;
use Scraper\Scraper\Annotation\UrlAnnotation;
use Scraper\Scraper\Cache\ICache;
use Scraper\Scraper\ContentType\IContentType;
use Scraper\Scraper\Protocol\IProtocol;
use Scraper\Scraper\Request\Request;

class Client
{
	/**
	 * @var bool
	 */
	protected $cache = false;

	/**
	 * @var ICache
	 */
	protected $cacheModel;

	/**
	 * @param Request $request
	 *
	 * @return mixed
	 * @throws \Doctrine\Common\Annotations\AnnotationException
	 * @throws \ReflectionException
	 */
	public function api(Request $request)
	{
		$reflectClassRequest = new \ReflectionClass($request);
		$className           = $reflectClassRequest->getShortName();
		$className           = str_replace('Request', 'Api', $className);
		$namespace           = str_replace('\Request', '\Api\\', $reflectClassRequest->getNamespaceName());
		/** @var UrlAnnotation $urlAnnotation */
		$urlAnnotation = Reader::read($request);
		$data          = $this->fetchData($request, $reflectClassRequest, $urlAnnotation);

		$reflectClassName = new \ReflectionClass($namespace . $className);
		$instance         = $reflectClassName->newInstanceArgs([$request, $data, $urlAnnotation]);
		return call_user_func([$instance, 'execute']);
	}

	/**
	 * @return $this
	 */
	public function enableCache(ICache $cache)
	{
		$this->cache      = true;
		$this->cacheModel = $cache;
		return $this;
	}

	/**
	 * @param Request          $request
	 * @param \ReflectionClass $reflectionClass
	 * @param UrlAnnotation    $urlAnnotation
	 *
	 * @return Response
	 */
	private function fetchData(Request $request, \ReflectionClass $reflectionClass, UrlAnnotation $urlAnnotation)
	{
		try {
			$cacheRequest = false;
			if ($this->cache && $this->cacheModel instanceof ICache && $this->cacheModel->exist($request, $reflectionClass)) {
				$cacheRequest = true;
				$response     = new Response(200, [], $this->cacheModel->get($request, $reflectionClass));
			}
			if (!$cacheRequest) {
				/** @var IProtocol $class */
				$class = (new \ReflectionClass('\Scraper\\Scraper\\Protocol\\' . ucfirst(strtolower($urlAnnotation->protocol))))
					->newInstanceArgs([$reflectionClass, $request, $urlAnnotation]);
				/** @var Response $response */
				$response = $class->execute();
			}
		} catch (\Exception $e) {
			echo 'Exception: ', $e->getMessage(), "\n";
		}

		try {
			if (in_array(strtolower($urlAnnotation->contentType), ['html', 'json', 'text', 'xml'])) {
				/** @var IContentType $class */
				$class = (new \ReflectionClass('\Scraper\\Scraper\\ContentType\\' . ucfirst(strtolower($urlAnnotation->contentType))))
					->newInstanceArgs([$reflectionClass, $request, $response, $urlAnnotation, $this->cacheModel]);
			} else {
				/** @var IContentType $class */
				$class = (new \ReflectionClass($urlAnnotation->contentType))
					->newInstanceArgs([$reflectionClass, $response, $urlAnnotation]);
			}

			/** @var Response $response */
			$response = $class->execute();
		} catch (\Exception $e) {
			echo 'Exception: ', $e->getMessage(), "\n";
		}

		return $response;
	}
}

<?php

namespace Scraper\Scraper\Annotation;

use Doctrine\Common\Annotations\Annotation\Enum;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class UrlAnnotation
 * @package Scraper\Scraper\Annotation
 *
 * @Annotation
 * @Target("CLASS")
 */
class UrlAnnotation
{
	static $METHOD_GET  = "GET";
	static $METHOD_POST = "POST";
	static $METHOD_PUT  = "PUT";

	static $PROTOCOL_HTTP = "HTTP";
	static $PROTOCOL_CURL = "CURL";
	static $PROTOCOL_SOAP = "SOAP";
	/**
	 * @var string
	 */
	public $baseUrl;

	/**
	 * @var string
	 */
	public $url;

	/**
	 * @var string
	 * @Enum({"HTTP", "SOAP", "CURL"})
	 */
	public $protocol;

	/**
	 * @var string
	 * @Enum({"POST", "GET", "PUT"})
	 */
	public $method;

	/**
	 * @var string
	 */
	public $contentType;

	/**
	 * @return string
	 */
	public function getFullUrl()
	{
		return $this->baseUrl . $this->url;
	}
}

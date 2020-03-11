<?php

namespace Scraper\Scraper\Annotation;

use Doctrine\Common\Annotations\Annotation\Enum;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class UrlAnnotation
 *
 * @Annotation
 * @Target("CLASS")
 */
class UrlAnnotation
{
    public static $METHOD_GET  = 'GET';
    public static $METHOD_POST = 'POST';
    public static $METHOD_PUT  = 'PUT';

    public static $PROTOCOL_HTTP  = 'HTTP';
    public static $PROTOCOL_CURL  = 'CURL';
    public static $PROTOCOL_REST  = 'REST';
    public static $PROTOCOL_SOAP  = 'SOAP';
    public static $PROTOCOL_OAUTH = 'OAUTH';
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
     * @Enum({"HTTP", "SOAP", "CURL", "OAUTH"})
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

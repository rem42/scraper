<?php

namespace Scraper\Scraper\Protocol;

use Scraper\Scraper\Annotation\UrlAnnotation;
use Scraper\Scraper\HttpClient\HttpClient;
use Scraper\Scraper\Request\Request;

abstract class Protocol implements IProtocol
{
    /** @var \ReflectionClass */
    protected $reflexionClass;
    /** @var Request */
    protected $request;
    /** @var UrlAnnotation */
    protected $urlAnnotation;
    /** @var HttpClient */
    private $httpClient;

    /**
     * Protocol constructor.
     */
    public function __construct(\ReflectionClass $reflexionClass, Request $request, UrlAnnotation $urlAnnotation)
    {
        $this->reflexionClass = $reflexionClass;
        $this->request        = $request;
        $this->urlAnnotation  = $urlAnnotation;
    }

    /**
     * @return HttpClient
     */
    protected function getHttpClient()
    {
        if (null === $this->httpClient) {
            $this->httpClient = new HttpClient();
        }

        return $this->httpClient;
    }

    /**
     * @return string
     */
    protected function makeUrl()
    {
        $url = $this->urlAnnotation->getFullUrl();

        if (sizeof($this->request->getParameters()) > 0) {
            $url .= '?';
            $url .= http_build_query($this->request->getParameters(), null, '&', PHP_QUERY_RFC3986);
        }
        return $url;
    }
}

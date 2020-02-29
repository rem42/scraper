<?php

namespace Scraper\Scraper;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Scraper\Scraper\Annotation\Reader;
use Scraper\Scraper\Annotation\UrlAnnotation;
use Scraper\Scraper\Cache\Cache;
use Scraper\Scraper\ContentType\IContentType;
use Scraper\Scraper\Protocol\IProtocol;
use Scraper\Scraper\Request\Request;

class Client
{
    protected bool $cache = false;

    protected Cache $cacheModel;

    private function generateRequest(): RequestInterface
    {
    }

    /**
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

    public function enableCache(Cache $cache): self
    {
        $this->cache      = true;
        $this->cacheModel = $cache;
        return $this;
    }

    private function fetchData(Request $request, \ReflectionClass $reflectionClass, UrlAnnotation $urlAnnotation): Response
    {
        if ($this->cache && $this->cacheModel instanceof Cache) {
            $this->cacheModel->setRequest($request);
            $this->cacheModel->setReflectionClass($reflectionClass);
        } else {
            $this->cache = false;
        }

        try {
            $cacheRequest = false;

            if ($this->cache && $this->cacheModel->exist()) {
                $cacheRequest = true;
                $response     = new Response(200, [], $this->cacheModel->get());
            }

            if (!$cacheRequest) {
                /** @var IProtocol $class */
                $class = (new \ReflectionClass('\Scraper\\Scraper\\Protocol\\' . ucfirst(strtolower($urlAnnotation->protocol))))
                    ->newInstanceArgs([$reflectionClass, $request, $urlAnnotation]);
                /** @var Response $response */
                $response = $class->execute();
            }
        } catch (\Exception $e) {
            echo 'Exception (request): ', $e->getMessage(), "\n";
        }

        try {
            if (in_array(strtolower($request->getContentType()), ['html', 'json', 'text', 'xml'])) {
                /** @var IContentType $class */
                $class = (new \ReflectionClass('\Scraper\\Scraper\\ContentType\\' . ucfirst(strtolower($request->getContentType()))))
                    ->newInstanceArgs([$reflectionClass, $request, $response, $urlAnnotation, $this->cacheModel]);
            } elseif (in_array(strtolower($urlAnnotation->contentType), ['html', 'json', 'text', 'xml'])) {
                /** @var IContentType $class */
                $class = (new \ReflectionClass('\Scraper\\Scraper\\ContentType\\' . ucfirst(strtolower($urlAnnotation->contentType))))
                    ->newInstanceArgs([$reflectionClass, $request, $response, $urlAnnotation, $this->cacheModel]);
            } else {
                /** @var IContentType $class */
                $class = (new \ReflectionClass($urlAnnotation->contentType))
                    ->newInstanceArgs([$reflectionClass, $request, $response, $urlAnnotation]);
            }

            /** @var Response $response */
            $response = $class->execute();
        } catch (\Exception $e) {
            echo 'Exception (data): ', $e->getMessage(), "\n";
        }

        return $response;
    }
}

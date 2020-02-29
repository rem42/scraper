<?php

namespace Scraper\Scraper\Protocol;

use GuzzleHttp\Psr7\Response;

class Http extends Protocol
{
    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function execute()
    {
        try {
            $function = strtolower($this->urlAnnotation->method);
            /* @var Response $response */
            return $this->$function();
        } catch (\Exception $e) {
            echo 'Exception: ', $e->getMessage(), "\n";
        }
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return Response|mixed|\Psr\Http\Message\ResponseInterface
     */
    protected function get()
    {
        $client = $this->getHttpClient();
        return $client
            ->get($this->urlAnnotation->getFullUrl(), $this->request->getParameters(), $this->request->getHeaders());
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return Response|mixed|\Psr\Http\Message\ResponseInterface
     */
    protected function post()
    {
        $client = $this->getHttpClient();
        return $client
            ->post($this->urlAnnotation->getFullUrl(), $this->request->getParameters(), $this->request->getHeaders(), $this->request->getBody());
    }
}

<?php

namespace Scraper\Scraper\Protocol;

use GuzzleHttp\Psr7\Response;
use Scraper\Scraper\Annotation\UrlAnnotation;
use Scraper\Scraper\Request\RequestOauth;

class Oauth extends Protocol
{
    /**
     * @var RequestOauth
     */
    protected $request;

    /**
     * @return Response
     */
    public function execute()
    {
        $client = $this->getHttpClient();

        return $client
            ->request($this->makeUrl(), null, UrlAnnotation::$METHOD_GET, $this->generateSignature());
    }

    protected function generateSignature()
    {
        $arrayToImplode   = [];
        $arrayToImplode[] = $this->urlAnnotation->method;
        $arrayToImplode[] = rawurlencode($this->urlAnnotation->getFullUrl());

        $parameters                           = $this->request->getParameters();
        $parameters['oauth_consumer_key']     = rawurlencode($this->request->getConsumerKey());
        $parameters['oauth_nonce']            = rawurlencode($this->request->nonce);
        $parameters['oauth_signature_method'] = rawurlencode($this->request->getSignatureMethod());
        $parameters['oauth_timestamp']        = rawurlencode($this->request->timestamp);
        $parameters['oauth_token']            = rawurlencode($this->request->getToken());
        $parameters['oauth_version']          = '1.0';

        $arrayToImplode[] = rawurlencode($this->_toByteValueOrderedQueryString($parameters));

        $base = implode('&', $arrayToImplode);

        $key           = rawurlencode($this->request->getConsumerSecret()) . '&' . rawurlencode($this->request->getTokenSecret());
        $signature     = base64_encode(hash_hmac('sha1', $base, $key, true));
        $authorization = 'OAuth';
        $authorization .= ' oauth_consumer_key=' . rawurlencode($this->request->getConsumerKey());
        $authorization .= ',oauth_token=' . rawurlencode($this->request->getToken());
        $authorization .= ',oauth_signature_method=' . rawurlencode($this->request->getSignatureMethod());
        $authorization .= ',oauth_timestamp=' . rawurlencode($this->request->timestamp);
        $authorization .= ',oauth_nonce=' . rawurlencode($this->request->nonce);
        $authorization .= ',oauth_version=1.0';
        $authorization .= ',oauth_signature=' . $signature;

        $headers                  = $this->request->getHeaders();
        $headers['Authorization'] = $authorization;

        return $headers;
    }

    protected function _toByteValueOrderedQueryString(array $params)
    {
        $return = array();
        uksort($params, 'strnatcmp');

        foreach ($params as $key => $value) {
            if (is_array($value)) {
                natsort($value);

                foreach ($value as $keyduplicate) {
                    $return[] = rawurlencode($key) . '=' . $keyduplicate;
                }
            } else {
                $return[] = rawurlencode($key) . '=' . $value;
            }
        }
        return implode('&', $return);
    }
}

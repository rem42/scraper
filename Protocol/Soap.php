<?php

namespace Scraper\Scraper\Protocol;

use GuzzleHttp\Psr7\Response;

class Soap extends Protocol
{
	/**
	 * @return Response
	 */
	public function execute()
	{
		$soap         = new \SoapClient($this->urlAnnotation->getFullUrl());
		$responseSoap = $soap->__doRequest($this->request->getBody()[0], $this->urlAnnotation->getFullUrl(), 'generateLabel', '2.0', 0);
		return new Response(null, [], $responseSoap);
	}
}

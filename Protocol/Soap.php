<?php

namespace Scraper\Scraper\Protocol;

use GuzzleHttp\Psr7\Response;
use Scraper\Scraper\Request\RequestSoap;

class Soap extends Protocol
{
	/**
	 * @var RequestSoap
	 */
	protected $request;

	/**
	 * @return Response
	 */
	public function execute()
	{
		$soap = new \SoapClient($this->urlAnnotation->getFullUrl(), $this->request->getParameters());

		if (sizeof($this->request->getHeaders()) > 0) {
			$soap->__setSOAPHeaders($this->request->getHeaders());
		}
		if ($this->request->isDoRequest()) {
			$responseSoap = $soap->__doRequest($this->request->getBody()[0], $this->urlAnnotation->getFullUrl(), $this->request->getAction(), $this->request->getVersion(), 0);
		} else {
			$responseSoap = $soap->{$this->request->getAction()}($this->request->getBody());
		}

		if(is_object($responseSoap)){
			$responseSoap = serialize($responseSoap);
		}

		return new Response(null, [], $responseSoap);
	}
}

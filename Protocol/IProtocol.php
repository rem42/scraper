<?php

namespace Scraper\Scraper\Protocol;

use Psr\Http\Message\ResponseInterface;
use Scraper\Scraper\Annotation\UrlAnnotation;
use Scraper\Scraper\Request\IRequest;

interface IProtocol
{
	/**
	 * @return ResponseInterface
	 */
	public function execute();
}

<?php

namespace Scraper\Scraper\ContentType;

class Text extends ContentType
{
	public function execute()
	{
		$text = $this->response->getBody()->getContents();
		$this->setCache($text);
		return $text;
	}
}

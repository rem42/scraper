<?php

namespace Scraper\Scraper\ContentType;

class Json extends ContentType
{
	public function execute()
	{
		$body    = $this->response->getBody()->getContents();
		$content = json_decode($body, true);
		$this->setCache($body);
		if (JSON_ERROR_NONE === json_last_error()) {
			return $content;
		}
		return null;
	}
}

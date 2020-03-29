<?php

namespace Scraper\Scraper\ContentType;

class Json extends ContentType
{
    public function execute()
    {
        $body    = $this->response->getBody()->getContents();
        dump($body);
        $content = json_decode($body, true);
        $this->setCache($body);
        dump($content);
        die('');

        if (JSON_ERROR_NONE === json_last_error()) {
            return $content;
        }
        return null;
    }
}

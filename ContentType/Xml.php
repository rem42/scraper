<?php

namespace Scraper\Scraper\ContentType;

class Xml extends ContentType
{
    public function execute()
    {
        $body = $this->response->getBody()->getContents();

        if ('' != $body) {
            $this->setCache($body);
            libxml_clear_errors();
            libxml_use_internal_errors(true);
            return simplexml_load_string($body, 'SimpleXMLElement', LIBXML_NOCDATA);
        }
        return null;
    }
}

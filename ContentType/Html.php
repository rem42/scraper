<?php

namespace Scraper\Scraper\ContentType;

use Symfony\Component\DomCrawler\Crawler;

class Html extends ContentType
{
    public function execute()
    {
        $crawler = new Crawler();
        $body    = $this->response->getBody()->getContents();
        $crawler->addHtmlContent($body);
        $this->setCache($body);
        return $crawler;
    }
}

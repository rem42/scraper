<?php

namespace Scraper\Scraper\Api;

use Scraper\Scraper\Annotation\UrlAnnotation;
use Scraper\Scraper\Request\Request;

interface ApiInterface
{
    public function __construct(Request $request, $data, UrlAnnotation $urlAnnotation);

    /**
     * @return $object
     */
    public function execute();
}

<?php

namespace Scraper\Scraper\Request;

interface IRequest
{
    /**
     * @return array
     */
    public function getBody();

    /**
     * @return array
     */
    public function getHeaders();

    /**
     * @return array
     */
    public function getParameters();
}

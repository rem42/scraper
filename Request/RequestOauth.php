<?php

namespace Scraper\Scraper\Request;

abstract class RequestOauth extends Request implements IRequestOauth
{
    /**
     * @var string
     */
    public $timestamp;
    /**
     * @var string
     */
    public $nonce;
}

<?php

namespace Scraper\Scraper\Request;

interface IRequestOauth
{
    public function getConsumerKey(): string;

    public function getConsumerSecret(): string;

    public function getToken(): string;

    public function getTokenSecret(): string;

    public function getSignatureMethod(): string;

    public function getAuthType(): string;
}

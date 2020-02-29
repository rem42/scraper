<?php

namespace Scraper\Scraper\Request;

interface IRequestSoap
{
    public function isDoRequest(): bool;

    public function getAction(): string;

    public function getVersion(): string;
}

<?php

namespace Scraper\Scraper\Request;

interface IRequestSoap
{
    public function isDoRequest(): bool;

    public function isLoginNeed(): bool;

    public function isRequestSpecific(): bool;

    public function getAction(): string;

    public function getVersion(): ?string;
}

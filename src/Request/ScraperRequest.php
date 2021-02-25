<?php

namespace Scraper\Scraper\Request;

abstract class ScraperRequest
{
    private ?bool $ssl = null;

    public function enableSSL(): self
    {
        $this->ssl = true;

        return $this;
    }

    public function disableSSL(): self
    {
        $this->ssl = false;

        return $this;
    }

    public function isSsl(): ?bool
    {
        return $this->ssl;
    }
}

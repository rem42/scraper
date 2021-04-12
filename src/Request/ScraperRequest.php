<?php

namespace Scraper\Scraper\Request;

abstract class ScraperRequest
{
    private ?bool $ssl       = null;
    private ?bool $authBasic = null;

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

    public function isAuthBasic(): ?bool
    {
        return $this->authBasic;
    }

    public function enableAuthBasic(): self
    {
        $this->authBasic = true;

        return $this;
    }

    public function disableAuthBasic(): self
    {
        $this->authBasic = false;

        return $this;
    }
}

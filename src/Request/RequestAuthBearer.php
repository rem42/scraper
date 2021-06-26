<?php

namespace Scraper\Scraper\Request;

interface RequestAuthBearer
{
    public function getBearer(): string;
}

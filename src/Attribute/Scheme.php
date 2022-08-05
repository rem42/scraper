<?php declare(strict_types=1);

namespace Scraper\Scraper\Attribute;

enum Scheme: string
{
    case HTTP = 'HTTP';
    case HTTPS = 'HTTPS';
}

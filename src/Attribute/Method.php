<?php declare(strict_types=1);

namespace Scraper\Scraper\Attribute;

enum Method: string
{
    case DELETE = 'DELETE';
    case GET = 'GET';
    case PATCH = 'PATCH';
    case POST = 'POST';
    case PUT = 'PUT';
}

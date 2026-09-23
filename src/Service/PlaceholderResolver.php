<?php

declare(strict_types=1);

namespace Scraper\Scraper\Service;

use Scraper\Scraper\Request\ScraperRequest;

final class PlaceholderResolver
{
    public static function resolve(string $value, ScraperRequest $request): string
    {
        if (preg_match_all('/\{([^}]+)}/', $value, $matches)) {
            foreach ($matches[1] as $match) {
                $method = 'get' . ucfirst($match);

                if (!method_exists($request, $method)) {
                    continue;
                }

                $tmp = $request->{$method}();

                if (is_object($tmp)) {
                    if (method_exists($tmp, '__toString')) {
                        $requestValue = (string) $tmp;
                    } else {
                        $requestValue = '';
                    }
                } elseif (is_scalar($tmp) || null === $tmp) {
                    $requestValue = (string) $tmp;
                } else {
                    $requestValue = '';
                }

                $value = str_replace('{' . $match . '}', $requestValue, $value);
            }
        }

        return $value;
    }
}

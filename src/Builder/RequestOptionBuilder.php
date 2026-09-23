<?php

declare(strict_types=1);

namespace Scraper\Scraper\Builder;

use Scraper\Scraper\Request\RequestAuthBasic;
use Scraper\Scraper\Request\RequestAuthBearer;
use Scraper\Scraper\Request\RequestBody;
use Scraper\Scraper\Request\RequestBodyJson;
use Scraper\Scraper\Request\RequestHeaders;
use Scraper\Scraper\Request\RequestQuery;
use Scraper\Scraper\Request\ScraperRequest;

final class RequestOptionBuilder
{
    /**
     * @return array<string, array<int|string, mixed>|object|resource|string>
     */
    public static function build(ScraperRequest $request): array
    {
        $options = [];

        if ($request instanceof RequestAuthBearer) {
            $options['auth_bearer'] = $request->getBearer();
        }

        if ($request instanceof RequestAuthBasic && false !== $request->isAuthBasic()) {
            $options['auth_basic'] = $request->getAuthBasic();
        }

        if ($request instanceof RequestHeaders) {
            $options['headers'] = $request->getHeaders();
        }

        if ($request instanceof RequestQuery) {
            $options['query'] = $request->getQuery();
        }

        if ($request instanceof RequestBody) {
            $options['body'] = $request->getBody();
        }

        if ($request instanceof RequestBodyJson) {
            $options['json'] = $request->getJson();
        }

        if (isset($options['json'], $options['body'])) {
            unset($options['body']);
        }

        return $options;
    }
}

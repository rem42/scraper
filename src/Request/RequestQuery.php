<?php

declare(strict_types=1);

namespace Scraper\Scraper\Request;

use App\Request\RequestOptionProvider;

class RequestQuery implements RequestOptionProvider
{
    /**
     * @var array<int|string, string>
     */
    private array $query;

    /**
     * @param array<int|string, string> $query
     */
    public function __construct(array $query)
    {
        $this->query = $query;
    }

    /**
     * @return array<int|string, string>
     */
    public function getQuery(): array
    {
        return $this->query;
    }

    public function getOptions(): array
    {
        return [
            'query' => $this->getQuery(),
        ];
    }
}

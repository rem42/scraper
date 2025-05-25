<?php

declare(strict_types=1);

namespace Scraper\Scraper\Request;

use App\Request\RequestOptionProvider;

class RequestBodyJson implements RequestOptionProvider
{
    /**
     * @var array<int|string, mixed>
     */
    private array $json;

    /**
     * @param array<int|string, mixed> $json
     */
    public function __construct(array $json)
    {
        $this->json = $json;
    }

    /**
     * @return array<int|string, mixed>
     */
    public function getJson(): array
    {
        return $this->json;
    }

    public function getOptions(): array
    {
        return [
            'json' => $this->getJson(),
        ];
    }
}

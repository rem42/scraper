<?php

declare(strict_types=1);

namespace Scraper\Scraper\Request;

use App\Request\RequestOptionProvider;

class RequestBody implements RequestOptionProvider
{
    /**
     * @var array<string, string>|resource|string
     */
    private $body;

    /**
     * @param array<string, string>|resource|string $body
     */
    public function __construct($body)
    {
        $this->body = $body;
    }

    /**
     * @return array<string, string>|resource|string
     */
    public function getBody()
    {
        return $this->body;
    }

    public function getOptions(): array
    {
        return [
            'body' => $this->getBody(),
        ];
    }
}

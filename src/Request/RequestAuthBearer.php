<?php

declare(strict_types=1);

namespace Scraper\Scraper\Request;

use App\Request\RequestOptionProvider;

class RequestAuthBearer implements RequestOptionProvider
{
    public function __construct(
        private readonly string $bearerToken,
    ) {
    }

    public function getBearer(): string
    {
        return $this->bearerToken;
    }

    public function getOptions(): array
    {
        return [
            'auth_bearer' => $this->getBearer(),
        ];
    }
}

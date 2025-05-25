<?php

declare(strict_types=1);

namespace Scraper\Scraper\Request;

use App\Request\RequestOptionProvider;

class RequestAuthBasic implements RequestOptionProvider
{
    public function __construct(
        private readonly string $login,
        private readonly string $password,
    ) {
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    // This method was used in the original buildOptions method.
    // We need to ensure its logic is preserved.
    // Assuming it should always return true if this object exists.
    public function isAuthBasic(): bool
    {
        return true; 
    }

    public function getAuthBasic(): array
    {
        return [$this->login, $this->password];
    }

    public function getOptions(): array
    {
        // Check isAuthBasic to maintain compatibility with original logic
        if (false === $this->isAuthBasic()) {
            return [];
        }
        return [
            'auth_basic' => $this->getAuthBasic(),
        ];
    }
}

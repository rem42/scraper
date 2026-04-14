<?php

declare(strict_types=1);

namespace Scraper\Scraper\Attribute;

use Scraper\Scraper\Exception\ClassNotInitializedException;
use Scraper\Scraper\Request\ScraperRequest;

final class ExtractAttribute
{
    /** @var \ReflectionClass<ScraperRequest> */
    private \ReflectionClass $reflectionClass;
    private Scraper $scraperAttribute;
    private bool $hasScraperAttribute = false;

    public function __construct(
        private readonly ScraperRequest $request,
    ) {
        $this->reflectionClass = new \ReflectionClass($request::class);
        $this->scraperAttribute = new Scraper();
    }

    public static function extract(ScraperRequest $request): Scraper
    {
        $self = new self($request);

        $self->traverseHierarchy();

        return $self->getScraperAnnotation();
    }

    private function getScraperAnnotation(): Scraper
    {
        if (!$this->hasScraperAttribute) {
            throw new ClassNotInitializedException('Class Scraper not found in Request class');
        }

        if (true === $this->request->isSsl()) {
            $this->scraperAttribute->scheme = Scheme::HTTPS;
        }

        if (false === $this->request->isSsl()) {
            $this->scraperAttribute->scheme = Scheme::HTTP;
        }

        return $this->scraperAttribute;
    }

    /**
     * @param \ReflectionClass<ScraperRequest>|null $reflectionClass
     */
    /**
     * Parcourt la hiérarchie de classes pour récupérer l'attribut Scraper.
     *
     * @param \ReflectionClass<ScraperRequest>|null $reflectionClass
     */
    private function traverseHierarchy(?\ReflectionClass $reflectionClass = null): void
    {
        if (null === $reflectionClass) {
            $reflectionClass = $this->reflectionClass;
        }
        $parentClass = $reflectionClass->getParentClass();

        if ($parentClass) {
            $this->traverseHierarchy($parentClass);
        }

        $attributes = $reflectionClass->getAttributes(Scraper::class);

        if (1 === \count($attributes)) {
            $this->hasScraperAttribute = true;
            $this->extractAttribute($attributes[0]->newInstance());
        }
    }

    private function extractAttribute(Scraper $attribute): void
    {
        $scraper = new Scraper();

        $this->initDefaultValues($scraper);

        /** @var array<string, mixed> $vars */
        $vars = get_object_vars($attribute);

        $this->extractChildValues($scraper, $vars);

        $this->scraperAttribute = $scraper;
    }

    private function initDefaultValues(Scraper $scraper): void
    {
        $vars = get_object_vars($this->scraperAttribute);

        // Initializing class properties
        foreach ($vars as $property => $value) {
            $scraper->{$property} = $value;
        }
    }

    /**
     * @param array<string, mixed> $vars
     */
    private function extractChildValues(Scraper $scraper, array $vars): void
    {
        foreach ($vars as $property => $value) {
            if (null === $value) {
                continue;
            }

            if (!\is_string($value)) {
                $scraper->{$property} = $value;
                continue;
            }
            $value = $this->replaceVariableInValue($value);

            if ('path' === $property) {
                $this->handlePath($scraper, $value);
                continue;
            }

            $scraper->{$property} = $value;
        }
    }

    private function replaceVariableInValue(string $value): string
    {
        // Match tokens like {name} but avoid greedy matches
        if (preg_match_all('/\{([^}]+)}/', $value, $matches)) {
            foreach ($matches[1] as $match) {
                $method = 'get' . ucfirst($match);

                // If the getter is not available on the Request, skip substitution
                if (!method_exists($this->request, $method)) {
                    // leave the placeholder as-is to make missing getters visible in tests/logs
                    continue;
                }

                $tmp = $this->request->{$method}();

                // Normalize to string safely: accept scalars and objects implementing __toString(),
                // otherwise fall back to empty string to avoid type errors in callers.
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

    private function handlePath(Scraper $scraper, ?string $path = null): void
    {
        if (null === $path) {
            return;
        }

        if ('' !== $path && '/' === $path[0]) {
            $scraper->path = $path;

            return;
        }

        if (isset($scraper->path)) {
            $scraper->path = rtrim($scraper->path, '/') . '/' . ltrim($path, '/');

            return;
        }
        $scraper->path = $path;
    }
}

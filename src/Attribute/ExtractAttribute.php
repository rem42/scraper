<?php

declare(strict_types=1);

namespace Scraper\Scraper\Attribute;

use Scraper\Scraper\Dto\ScraperConfig;
use Scraper\Scraper\Exception\ClassNotInitializedException;
use Scraper\Scraper\Exception\ScraperConfigurationException;
use Scraper\Scraper\Request\ScraperRequest;
use Scraper\Scraper\Service\PlaceholderResolver;

final class ExtractAttribute
{
    /** @var \ReflectionClass<ScraperRequest> */
    private readonly \ReflectionClass $reflectionClass;
    private Scraper $scraperAttribute;
    private bool $hasScraperAttribute = false;

    public function __construct(
        private readonly ScraperRequest $request,
    ) {
        $this->reflectionClass = new \ReflectionClass($request::class);
        $this->scraperAttribute = new Scraper();
    }

    public static function extract(ScraperRequest $request): ScraperConfig
    {
        $self = new self($request);

        $self->traverseHierarchy();

        $annotation = $self->getScraperAnnotation();

        if (null === $annotation->method) {
            throw new ScraperConfigurationException('Method not found');
        }

        if (null === $annotation->scheme) {
            throw new ScraperConfigurationException('scheme is required');
        }

        if (null === $annotation->host) {
            throw new ScraperConfigurationException('host is required');
        }

        return new ScraperConfig(
            $annotation->method,
            $annotation->scheme,
            $annotation->host,
            $annotation->path ?? '',
        );
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
            $value = PlaceholderResolver::resolve($value, $this->request);

            if ('path' === $property) {
                $this->handlePath($scraper, $value);
                continue;
            }

            $scraper->{$property} = $value;
        }
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

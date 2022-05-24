<?php

namespace Scraper\Scraper\Annotation;

use Doctrine\Common\Annotations\AnnotationReader;
use Scraper\Scraper\Exception\ClassNotInitializedException;
use Scraper\Scraper\Request\ScraperRequest;

final class ExtractAnnotation
{
    /** @var \ReflectionClass<ScraperRequest> */
    private \ReflectionClass $reflexionClass;
    private Scraper $scraperAnnotation;
    private bool $hasScraperAnnotation = false;

    public function __construct(
        protected AnnotationReader $reader,
        protected ScraperRequest $request
    ) {
        $this->reflexionClass    = new \ReflectionClass(\get_class($request));
        $this->scraperAnnotation = new Scraper();
    }

    public static function extract(ScraperRequest $request): Scraper
    {
        $self = new self(new AnnotationReader(), $request);

        $self->recursive();

        return $self->getScraperAnnotation();
    }

    /**
     * @param \ReflectionClass<ScraperRequest>|null $reflectionClass
     */
    private function recursive(\ReflectionClass $reflectionClass = null): void
    {
        if (null === $reflectionClass) {
            $reflectionClass = $this->reflexionClass;
        }
        $parentClass = $reflectionClass->getParentClass();

        if ($parentClass) {
            $this->recursive($parentClass);
        }

        $annotation = $this->reader->getClassAnnotation($reflectionClass, Scraper::class);

        if ($annotation instanceof Scraper) {
            $this->hasScraperAnnotation = true;
            $this->extractAnnotation($annotation);
        }
    }

    private function extractAnnotation(Scraper $annotation): void
    {
        $scraper = new Scraper();

        $this->initDefaultValues($scraper);

        $vars = get_object_vars($annotation);
        $this->extractChildValues($scraper, $vars);

        $this->scraperAnnotation = $scraper;
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

    private function getScraperAnnotation(): Scraper
    {
        if (!$this->hasScraperAnnotation) {
            throw new ClassNotInitializedException('Class Scraper not found in Request class');
        }

        if (true === $this->request->isSsl()) {
            $this->scraperAnnotation->scheme = 'HTTPS';
        }

        if (false === $this->request->isSsl()) {
            $this->scraperAnnotation->scheme = 'HTTP';
        }

        return $this->scraperAnnotation;
    }

    private function initDefaultValues(Scraper $scraper): void
    {
        $vars = get_object_vars($this->scraperAnnotation);
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
        /**
         * @var string $property
         * @var string $value
         */
        foreach ($vars as $property => $value) {
            $value = $this->replaceVariableInValue($value);

            if ('path' === $property) {
                $this->handlePath($scraper, $value);
                continue;
            }

            $scraper->{$property} = $value;
        }
    }

    public function replaceVariableInValue(string $value): string
    {
        if (preg_match_all('#{(.*?)}#', $value, $matchs)) {
            foreach ($matchs[1] as $match) {
                $method       = 'get' . ucfirst($match);
                $requestValue = $this->request->{$method}();
                $value        = str_replace('{' . $match . '}', $requestValue, $value);
            }
        }
        return $value;
    }
}

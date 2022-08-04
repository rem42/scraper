<?php declare(strict_types=1);

namespace Scraper\Scraper\Attribute;

use Scraper\Scraper\Exception\ClassNotInitializedException;
use Scraper\Scraper\Request\ScraperRequest;

final class ExtractAttribute
{
    /** @var \ReflectionClass<ScraperRequest> */
    private \ReflectionClass $reflexionClass;
    private Scraper $scraperAttribute;
    private bool $hasScraperAttribute = false;

    public function __construct(
        protected ScraperRequest $request
    ) {
        $this->reflexionClass   = new \ReflectionClass($request::class);
        $this->scraperAttribute = new Scraper();
    }

    public static function extract(ScraperRequest $request): Scraper
    {
        $self = new self($request);

        $self->recursive();

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
    private function recursive(\ReflectionClass $reflectionClass = null): void
    {
        if (null === $reflectionClass) {
            $reflectionClass = $this->reflexionClass;
        }
        $parentClass = $reflectionClass->getParentClass();

        if ($parentClass) {
            $this->recursive($parentClass);
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
        /**
         * @var string $property
         * @var string $value
         */
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
        if (preg_match_all('#{(.*?)}#', $value, $matchs)) {
            foreach ($matchs[1] as $match) {
                $method       = 'get' . ucfirst($match);
                $requestValue = (string) $this->request->{$method}();
                $value        = str_replace('{' . $match . '}', $requestValue, $value);
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

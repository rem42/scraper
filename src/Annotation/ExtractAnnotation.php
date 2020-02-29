<?php

namespace Scraper\Scraper\Annotation;

use Doctrine\Common\Annotations\AnnotationReader;
use Scraper\Scraper\Request\ScraperRequest;

class ExtractAnnotation
{
    private \ReflectionClass $reflexionClass;
    private AnnotationReader $reader;
    private ScraperRequest $request;
    private Scraper $scraperAnnotation;

    public function __construct(AnnotationReader $reader, ScraperRequest $request)
    {
        $this->reflexionClass    = new \ReflectionClass(get_class($request));
        $this->reader            = $reader;
        $this->request           = $request;
        $this->scraperAnnotation = new Scraper();
    }

    public static function extract(ScraperRequest $request): Scraper
    {
        $self = new self(new AnnotationReader(), $request);

        $self->recursive();

        return $self->getScraperAnnotation();
    }

    protected function recursive(\ReflectionClass $reflectionClass =  null): void
    {
        if (null === $reflectionClass) {
            $reflectionClass = $this->reflexionClass;
        }
        $parentClass = $reflectionClass->getParentClass();

        if ($parentClass) {
            $this->recursive($parentClass);
        }

        if ($annotation = $this->reader->getClassAnnotation($reflectionClass, Scraper::class)) {
            $this->extractAnnotation($annotation);
        }
    }

    protected function extractAnnotation(Scraper $annotation): void
    {
        $scraper = new Scraper();

        // Initializing class properties
        foreach ($this->scraperAnnotation as $property => $value) {
            $scraper->$property = $value;
        }

        foreach ($annotation as $property => $value) {
            $scraper->$property = $value;
        }

        $this->scraperAnnotation = $scraper;
    }

    public function getScraperAnnotation(): Scraper
    {
        return $this->scraperAnnotation;
    }
}

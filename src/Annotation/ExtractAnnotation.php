<?php

namespace Scraper\Scraper\Annotation;

use Doctrine\Common\Annotations\AnnotationReader;
use Scraper\Scraper\Request\ScraperRequest;

class ExtractAnnotation
{
    /** @var \ReflectionClass<ScraperRequest> */
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

    /**
     * @param \ReflectionClass<ScraperRequest>|null $reflectionClass
     */
    protected function recursive(\ReflectionClass $reflectionClass =  null): void
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
            $this->extractAnnotation($annotation);
        }
    }

    protected function extractAnnotation(Scraper $annotation): void
    {
        $scraper = new Scraper();

        $vars = get_object_vars($this->scraperAnnotation);
        // Initializing class properties
        foreach ($vars as $property => $value) {
            $scraper->$property = $value;
        }

        $vars = get_object_vars($annotation);

        foreach ($vars as $property => $value) {
            if (preg_match_all('#{(.*?)}#', $value, $matchs)) {
                foreach ($matchs[1] as $match) {
                    $method       = 'get' . ucfirst($match);
                    $requestValue = $this->request->$method();
                    $value        = str_replace('{' . $match . '}', $requestValue, $value);
                }
            }
            $scraper->$property = $value;
        }

        $this->scraperAnnotation = $scraper;
    }

    public function getScraperAnnotation(): Scraper
    {
        return $this->scraperAnnotation;
    }
}

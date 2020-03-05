<?php

namespace Scraper\Scraper\Annotation;

use Doctrine\Common\Annotations\AnnotationReader;
use Scraper\Scraper\Request\Request;

class Reader
{
    /**
     * @var \ReflectionClass
     */
    private $reflectionClass;
    /**
     * @var AnnotationReader
     */
    private $reader;
    /**
     * @var Request
     */
    private $class;

    /**
     * Reader constructor.
     *
     * @param Request          $class
     * @param AnnotationReader $reader
     *
     * @throws \ReflectionException
     */
    public function __construct(Request $class, AnnotationReader $reader)
    {
        $this->reflectionClass = new \ReflectionClass(get_class($class));
        $this->reader          = $reader;
        $this->class           = $class;
    }

    /**
     * @param Request $class
     *
     * @return mixed|UrlAnnotation
     *
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \ReflectionException
     */
    public static function read(Request $class)
    {
        $self = new self($class, new AnnotationReader());

        $urlAnnotation = $self->revursiveReader($self->getReflectionClass(), new UrlAnnotation());

        if (null !== $class->getAnnotationContentType()) {
            $urlAnnotation->contentType = $class->getAnnotationContentType();
        }

        if (null !== $class->getProtocol()) {
            $urlAnnotation->protocol = $class->getProtocol();
        }

        return $urlAnnotation;
    }

    /**
     * @param \ReflectionClass $reflectionClass
     * @param UrlAnnotation    $urlAnnotation
     *
     * @return mixed|UrlAnnotation
     */
    public function revursiveReader(\ReflectionClass $reflectionClass, UrlAnnotation $urlAnnotation)
    {
        $reflectionParentClass = $reflectionClass->getParentClass();

        if ($reflectionParentClass && $this->reader->getClassAnnotation($reflectionParentClass, UrlAnnotation::class)) {
            $annotationParent = $this->revursiveReader($reflectionParentClass, $urlAnnotation);
            $urlAnnotation    = $this->work($urlAnnotation, $annotationParent);
        }

        if ($annotation = $this->reader->getClassAnnotation($reflectionClass, UrlAnnotation::class)) {
            $urlAnnotation = $this->work($urlAnnotation, $annotation);
        }
        return $urlAnnotation;
    }

    /**
     * @param $urlAnnotation
     * @param $annotation
     *
     * @return mixed
     */
    private function work($urlAnnotation, $annotation)
    {
        foreach ($annotation as $item => $value) {
            if ($annotation->$item && $annotation->$item !== null) {
                if (preg_match_all('#{(.*?)}#', $value, $matchs)) {
                    foreach ($matchs[1] as $match) {
                        $method       = 'get' . ucfirst($match);
                        $requestValue = $this->class->$method();
                        $value        = str_replace('{' . $match . '}', $requestValue, $value);
                    }
                }
                $urlAnnotation->$item = $value;
            }
        }
        return $urlAnnotation;
    }

    /**
     * @return \ReflectionClass
     */
    public function getReflectionClass()
    {
        return $this->reflectionClass;
    }
}

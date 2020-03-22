<?php

namespace Scraper\Scraper\Api;

use Doctrine\Common\Annotations\AnnotationReader;
use Scraper\Scraper\Annotation\Scraper;
use Scraper\Scraper\Request\ScraperRequest;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\HttpClient\ResponseInterface;

abstract class AbstractApi implements ApiInterface
{
    protected ScraperRequest $request;
    protected Scraper $scraper;
    protected ResponseInterface $response;
    protected Serializer $serializer;

    public function __construct(ScraperRequest $request, Scraper $scraper, ResponseInterface $response)
    {
        $this->request  = $request;
        $this->scraper  = $scraper;
        $this->response = $response;

        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));

        $metadataAwareNameConverter = new MetadataAwareNameConverter($classMetadataFactory);

        $encoders    = ['json' => new JsonEncoder()];
        $normalizers = [new ObjectNormalizer($classMetadataFactory, $metadataAwareNameConverter, null, new ReflectionExtractor()), new ArrayDenormalizer()];

        $this->serializer = new Serializer($normalizers, $encoders);
    }
}

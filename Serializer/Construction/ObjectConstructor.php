<?php

namespace Scraper\Scraper\Serializer\Construction;

use JMS\Serializer\Construction\ObjectConstructorInterface;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\Metadata\ClassMetadata;
use JMS\Serializer\VisitorInterface;

class ObjectConstructor implements ObjectConstructorInterface
{
    /**
     * @param VisitorInterface       $visitor
     * @param ClassMetadata          $metadata
     * @param mixed                  $data
     * @param array                  $type
     * @param DeserializationContext $context
     *
     * @return mixed|object
     */
    public function construct(VisitorInterface $visitor, ClassMetadata $metadata, $data, array $type, DeserializationContext $context)
    {
        $className = $metadata->name;
        return new $className();
    }
}

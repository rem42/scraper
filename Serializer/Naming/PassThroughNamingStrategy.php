<?php

namespace Scraper\Scraper\Serializer\Naming;

use JMS\Serializer\Metadata\PropertyMetadata;
use JMS\Serializer\Naming\PropertyNamingStrategyInterface;

class PassThroughNamingStrategy implements PropertyNamingStrategyInterface
{
	/**
	 * Translates the name of the property to the serialized version.
	 *
	 * @param PropertyMetadata $property
	 *
	 * @return string
	 */
	public function translateName(PropertyMetadata $property)
	{
		return $property->name;
	}
}

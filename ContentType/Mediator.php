<?php

namespace Scraper\Scraper\ContentType;

class Mediator extends ContentType
{
    /**
     * @return mixed|void
     *
     * @throws \ReflectionException
     */
    public function execute()
    {
        $namespace        = str_replace('\Request', '\Mediator\\', $this->reflexionClass->getNamespaceName());
        $reflectClassName = new \ReflectionClass($namespace . $this->urlAnnotation->contentType);
        $instance         = $reflectClassName->newInstanceWithoutConstructor();
        $data             = call_user_func([$instance, 'execute'], $this->response);
        $this->setCache($data);
    }
}

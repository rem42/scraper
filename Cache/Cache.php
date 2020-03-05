<?php

namespace Scraper\Scraper\Cache;

use Scraper\Scraper\Request\Request;

abstract class Cache implements ICache
{
    /**
     * @var Request
     */
    protected $request;
    /**
     * @var \ReflectionClass
     */
    protected $reflectionClass;

    /**
     * @param Request $request
     *
     * @return $this
     */
    public function setRequest(?Request $request)
    {
        $request->removeFioritures();
        $this->request = $request;
        return $this;
    }

    /**
     * @param \ReflectionClass $reflectionClass
     *
     * @return $this
     */
    public function setReflectionClass(?\ReflectionClass $reflectionClass)
    {
        $this->reflectionClass = $reflectionClass;
        return $this;
    }
}

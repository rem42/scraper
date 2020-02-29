<?php

namespace Scraper\Scraper\Entity;

class Response
{
    protected $data;
    protected $object;
    protected $url;
    protected $request;

    public function getData()
    {
        return $this->data;
    }

    public function setData($data): self
    {
        $this->data = $data;
        return $this;
    }

    public function getObject()
    {
        return $this->object;
    }

    public function setObject($object): self
    {
        $this->object = $object;
        return $this;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function setRequest($request): self
    {
        $this->request = $request;
        return $this;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url): self
    {
        $this->url = $url;
        return $this;
    }
}

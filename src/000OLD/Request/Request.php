<?php

namespace Scraper\Scraper\Request;

abstract class Request implements IRequest
{
    /** @var bool */
    protected $isFile;
    /** @var string */
    protected $contentType;
    /** @var string */
    protected $annotationContentType;
    /** @var string */
    protected $protocol;

    /**
     * @return string
     */
    public function getAnnotationContentType(): ?string
    {
        return $this->annotationContentType;
    }

    /**
     * @param string $annotationContentType
     *
     * @return $this
     */
    public function setAnnotationContentType(?string $annotationContentType)
    {
        $this->annotationContentType = $annotationContentType;
        return $this;
    }

    /**
     * @return string
     */
    public function getContentType(): ?string
    {
        return $this->contentType;
    }

    /**
     * @param string $contentType
     *
     * @return $this
     */
    public function setContentType(?string $contentType)
    {
        $this->contentType = $contentType;
        return $this;
    }

    /**
     * @return string
     */
    public function getProtocol(): ?string
    {
        return $this->protocol;
    }

    /**
     * @param string $protocol
     *
     * @return $this
     */
    public function setProtocol(?string $protocol)
    {
        $this->protocol = $protocol;
        return $this;
    }

    /**
     * @return bool
     */
    public function isFile(): ?bool
    {
        return $this->isFile;
    }

    /**
     * @param bool $isFile
     *
     * @return $this
     */
    public function setIsFile(?bool $isFile)
    {
        $this->isFile = $isFile;
        return $this;
    }

    public function removeFioritures()
    {
        $this->parameters = null;
    }
}

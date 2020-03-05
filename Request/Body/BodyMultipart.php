<?php

namespace Scraper\Scraper\Request\Body;

use Doctrine\Common\Collections\ArrayCollection;

class BodyMultipart
{
    /**
     * @var ArrayCollection|Multipart
     */
    protected $multipart;

    /**
     * BodyMultipart constructor.
     */
    public function __construct()
    {
        $this->multipart = new ArrayCollection();
    }

    /**
     * @param Multipart $multipart
     */
    public function addMultipart(Multipart $multipart)
    {
        $this->multipart->add($multipart);
    }

    /**
     * @return ArrayCollection|Multipart
     */
    public function getMultipart()
    {
        return $this->multipart;
    }

    /**
     * @param ArrayCollection|Multipart $multipart
     *
     * @return $this
     */
    public function setMultipart($multipart)
    {
        $this->multipart = $multipart;
        return $this;
    }
}

<?php

namespace Scraper\Scraper\Request\Body;

class Multipart
{
	/**
	 * @var string
	 */
	protected $name;
	/**
	 * @var string
	 */
	protected $contents;
	/**
	 * @var string
	 */
	protected $filename;
	/**
	 * @var array
	 */
	protected $headers;

	/**
	 * @return string
	 */
	public function getName(): ?string
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 *
	 * @return $this
	 */
	public function setName(?string $name)
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getContents()
	{
		return $this->contents;
	}

	/**
	 * @param string $contents
	 *
	 * @return $this
	 */
	public function setContents($contents)
	{
		$this->contents = $contents;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getFilename(): ?string
	{
		return $this->filename;
	}

	/**
	 * @param string $filename
	 *
	 * @return $this
	 */
	public function setFilename(?string $filename)
	{
		$this->filename = $filename;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getHeaders(): ?array
	{
		return $this->headers;
	}

	/**
	 * @param array $headers
	 *
	 * @return $this
	 */
	public function setHeaders(?array $headers)
	{
		$this->headers = $headers;
		return $this;
	}
}

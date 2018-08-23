<?php

namespace Scraper\Scraper\Cache;

interface ICache
{
	/**
	 * @return boolean
	 */
	public function exist();

	/**
	 * @return mixed
	 */
	public function get();

	/**
	 * @param $data
	 *
	 * @return boolean
	 */
	public function write($data);
}

<?php

namespace Extras\Cache;

use Nette;

/**
 * Cache class
 *
 * @author Dávid Ďurika
 */
class Cache extends Nette\Caching\Cache
{

	/**
	 * Generates internal cache key.
	 *
	 * @param  string
	 * @return string
	 */
	protected function generateKey($key)
	{
		if(!is_scalar($key) || strlen($key) > 80) {
			throw new \Nette\InvalidArgumentException();
		}

		return $this->namespace . $key;
	}

}
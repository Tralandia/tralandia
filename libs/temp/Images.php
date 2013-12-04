<?php

namespace DataParser\Variable;

use Nette\ArrayHash;

/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 04/12/13 14:35
 */

class Images extends ArrayHash implements \Serializable
{

	public function __toString()
	{
		return serialize($this);
	}


	/**************************** \Serializable ****************************/


	/**
	 * @internal
	 * @return string
	 */
	public function serialize()
	{
		return serialize((array) $this->getIterator());
	}



	/**
	 * @internal
	 * @param string $serialized
	 */
	public function unserialize($serialized)
	{
		$data = unserialize($serialized);

		foreach($data as $key => $value) {
			$this->$key = $value;
		}
	}

}

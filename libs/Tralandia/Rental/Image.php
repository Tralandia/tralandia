<?php
/**
 * This file is part of the Harvester.
 * User: david
 * Created at: 6/14/13 8:48 AM
 */

namespace Tralandia\Rental;

use Nette;


/**
 * @property string $filePath
 */
class Image extends \Tralandia\Lean\BaseEntity
{

	/**
	 * @return string
	 */
	public function getPath()
	{
		return $this->filePath;
	}

}

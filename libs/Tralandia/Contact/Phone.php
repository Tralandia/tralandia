<?php
/**
 * This file is part of the Harvester.
 * User: david
 * Created at: 6/14/13 8:48 AM
 */

namespace Tralandia\Contact;

use Nette;


/**
 * @property int $id
 * @property string $value
 * @property string $international
 * @property string $national
 */
class Phone extends \Tralandia\Lean\BaseEntity
{

	/**
	 * @return string
	 */
	public function __toString()
	{
		return "{$this->value}";
	}



}

<?php
/**
 * This file is part of the Harvester.
 * User: david
 * Created at: 6/14/13 8:48 AM
 */

namespace Tralandia\Rental;

use Nette;


/**
 * @property string $slug
 */
class RoomType extends \Tralandia\Lean\BaseEntity
{

	/**
	 * @return int
	 */
	public function getNameId()
	{
		return $this->row->name_id;
	}

}

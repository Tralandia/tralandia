<?php
/**
 * This file is part of the Harvester.
 * User: david
 * Created at: 6/14/13 8:48 AM
 */

namespace Tralandia\Rental;

use Nette;


/**
 * @property int $id
 * @property string $slug
 */
class Type extends \Tralandia\Lean\BaseEntity
{

	public function getNameId()
	{
		return $this->row->name_id;
	}

}

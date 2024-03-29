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
 * @property bool $important
 * @property \Tralandia\Rental\AmenityType $type m:hasOne(type_id)
 */
class Amenity extends \Tralandia\Lean\BaseEntity
{

	public function getNameId()
	{
		return $this->row->name_id;
	}

	/**
	 * @return int
	 */
	public function getTypeId()
	{
		return $this->row->type_id;
	}

}

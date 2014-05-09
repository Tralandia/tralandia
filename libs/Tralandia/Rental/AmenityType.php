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
 * @property int $sorting
 */
class AmenityType extends \Tralandia\Lean\BaseEntity
{

	/**
	 * @return int
	 */
	public function getNameId()
	{
		return $this->row->name_id;
	}



	public static $slugToId = [
		'children' => 2,
		'board' => 5,
		'service' => 6,
		'wellness' => 7,
		'kitchen' => 9,
		'bathroom' => 10,
		'contact-person-availability' => 15,
		'animal' => 17,
		'near-by' => 19,
		'rental-services' => 20,
		'on-premises' => 21,
		'sports-fun' => 22,
	];

}

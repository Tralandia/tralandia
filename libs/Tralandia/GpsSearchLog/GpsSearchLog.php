<?php
/**
 * This file is part of the Harvester.
 * User: david
 * Created at: 6/14/13 8:48 AM
 */

namespace Tralandia\GpsSearchLog;

use Nette;


/**
 * @property string $text
 * @property int $count
 * @property float $latitude
 * @property float $longitude
 * @property \Tralandia\Location\Location $primaryLocation m:hasOne(primaryLocation_id:)
 */
class GpsSearchLog extends \Tralandia\Lean\BaseEntity
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

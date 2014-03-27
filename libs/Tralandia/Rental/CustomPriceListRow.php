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
 * @property int $sort
 * @property \DateTime $seasonFrom
 * @property \DateTime $seasonTo
 * @property int $price
 * @property int $priceFor1
 * @property int $priceFor2
 */
class CustomPriceListRow extends \Tralandia\Lean\BaseEntity
{

	/**
	 * @return int
	 */
	public function getNoteId()
	{
		return $this->row->note_id;
	}

	public function getPriceFor()
	{
		return 'priceFor';
	}

}

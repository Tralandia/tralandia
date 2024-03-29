<?php
/**
 * This file is part of the Harvester.
 * User: david
 * Created at: 6/14/13 8:48 AM
 */

namespace Tralandia\Rental;

use Nette;


/**
 * @property int $roomCount
 * @property int $bedCount
 * @property int $extraBedCount
 * @property int $price
 * @property \Tralandia\Rental\RoomType $roomType m:hasOne(roomType_id:)
 * @property \Tralandia\Rental\Rental $rental m:hasOne(rental_id:)
 */
class PriceListRow extends \Tralandia\Lean\BaseEntity
{

	/**
	 * @return \Extras\Types\Price
	 */
	public function getPrice()
	{
		return new \Extras\Types\Price($this->row->price, $this->getCurrency());
	}


	public function setPrice(\Extras\Types\Price $price)
	{
		$this->price = $price->convertToFloat($this->getCurrency());

		return $this;
	}


	public function setFloatPrice($price)
	{
		$this->row->price = $price;
	}


	public function getCurrency()
	{
		return $this->rental->getSomeCurrency();
	}
}

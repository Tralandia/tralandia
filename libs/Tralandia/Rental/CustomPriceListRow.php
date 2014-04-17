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
 * @property \DateTime|null $seasonFrom
 * @property \DateTime|null $seasonTo
 * @property int $price
 * @property \Tralandia\Rental\PriceFor $priceFor m:hasOne(priceFor_id:)
 * @property \Tralandia\Rental\Rental $rental m:hasOne(rental_id:)
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

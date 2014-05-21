<?php

namespace Tralandia\Rental;

use Nette;


/**
 * @property int $sort
 */
class PriceFor extends \Tralandia\Lean\BaseEntity
{

	/**
	 * @return int
	 */
	public function getFirstPartId()
	{
		return $this->row->firstPart_id;
	}

	/**
	 * @return int
	 */
	public function getSecondPartId()
	{
		return $this->row->secondPart_id;
	}

}

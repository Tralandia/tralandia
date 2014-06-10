<?php

namespace Tralandia\Invoicing;

use Nette;
use Tralandia\Dictionary\Translatable;


/**
 * @property ServiceType $type m:hasOne(type_id)
 * @property ServiceDuration $duration m:hasOne(duration_id)
 * @property float|null $priceDefault
 * @property float|null $priceCurrent
 * @property \Tralandia\Currency $currency m:hasOne()
 * @property Company $company m:hasOne()
 * @property \Tralandia\Location\Location|NULL $primaryLocation m:hasOne(primaryLocation_id)
 */
class Service extends \Tralandia\Lean\BaseEntity
{


	/**
	 * @return bool
	 */
	public function isForFree()
	{
		return !$this->getPriceCurrent()->getSourceAmount();
	}


	/**
	 * @return Translatable
	 */
	public function getLabel()
	{
		$label = new Translatable();
		$label->phrase($this->type->getNameId())->string(' ')->phrase($this->duration->getNameId());

		return $label;
	}


	/**
	 * @return \Extras\Types\Price
	 */
	public function getPriceCurrent()
	{
		return new \Extras\Types\Price($this->row->priceCurrent, $this->currency);
	}


}

<?php

namespace Tralandia\Invoicing;

use Nette;


/**
 * @property ServiceType $type m:hasOne(type_id)
 * @property ServiceDuration $duration m:hasOne(duration_id)
 * @property float|null $priceDefault
 * @property float|null $priceCurrent
 * @property \Tralandia\Currency $currency m:hasOne()
 * @property Company $company m:hasOne()
 * @property \Tralandia\Location\Location $primaryLocation m:hasOne(primaryLocation_id)
 */
class Service extends \Tralandia\Lean\BaseEntity
{


	/**
	 * @return bool
	 */
	public function isForFree()
	{
		return !$this->priceCurrent;
	}

}

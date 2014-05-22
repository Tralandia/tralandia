<?php

namespace Tralandia\Invoicing;

use Nette;


/**
 * @property string|null $name
 * @property string|null $slug
 * @property string|null $address
 * @property string|null $address2
 * @property string|null $postcode
 * @property string|null $locality
 * @property \Tralandia\Location\Location $primaryLocation m:hasOne(primaryLocation_id)
 * @property string|null $companyId
 * @property string|null $companyVatId
 * @property float|null $vat
 * @property string|null $registrator
 * @property boolean $inEu = TRUE
 */
class Company extends \Tralandia\Lean\BaseEntity
{
	const SLUG_ZERO = 'zero';
	const SLUG_TRALANDIA_SRO = 'tralandiaSro';


}

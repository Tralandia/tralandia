<?php

namespace Tralandia\User;

use Nette;
use Nette\Utils\Arrays;
use Nette\Utils\Json;
use Tralandia\Invoicing\ClientInformation;


/**
 * @property string $login
 * @property array|null $invoicingInformation m:passThru(jsonIn|jsonOut)
 * @property \Tralandia\Language\Language $language m:hasOne
 * @property \Tralandia\Location\Location $primaryLocation m:hasOne(primaryLocation_id:)
 *
 * @property \Tralandia\Rental\Rental[] $rentals m:belongsToMany
 */
class User extends \Tralandia\Lean\BaseEntity
{

	const INVOICING_INFORMATION_DEFAULT_KEY = 'default';

	public function loadInvoicingInformation($value)
	{
		if(!isset($value['default'])) {
			$value['default'] = [];
		}

		foreach($value as $type => $info) {
			$value[$type] = ClientInformation::filter($info);
		}

		return $value;
	}

	public function getDefaultInvoicingInformation()
	{
		$info = $this->invoicingInformation;
		if(!isset($info['default'])) {
			$info['default'] = ClientInformation::filter([]);
		}

		return $info['default'];
	}


	public function setDefaultInvoicingInformation($info)
	{
		$invoicingInformation = $this->invoicingInformation;
		$invoicingInformation['default'] = ClientInformation::filter($info);
		$this->invoicingInformation = $invoicingInformation;
	}




	/**
	 * @return \Tralandia\Rental\Rental|null
	 */
	public function getFirstRental()
	{
		$rentals = $this->rentals;
		return count($rentals) ? reset($rentals) : NULL;
	}
}

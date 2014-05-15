<?php

namespace Tralandia\User;

use Nette;
use Nette\Utils\Arrays;
use Nette\Utils\Json;
use Tralandia\Invoicing\ClientInformation;


/**
 * @property string $login
 * @property \Tralandia\Invoicing\ClientInformation $invoicingInformation m:passThru(loadInvoicingInformation|)
 */
class User extends \Tralandia\Lean\BaseEntity
{

	const INVOICING_INFORMATION_DEFAULT_KEY = 'default';


	/**
	 * @param $string
	 *
	 * @return mixed
	 */
	public function loadInvoicingInformation($string)
	{
		if($string instanceof ClientInformation) return $string;

		$info = $string === null ? [] : Json::decode($string, Json::FORCE_ARRAY);
		return new ClientInformation($info, $this);
	}

}

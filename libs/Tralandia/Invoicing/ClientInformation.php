<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 14/05/14 15:15
 */

namespace Tralandia\Invoicing;


use Nette;

class ClientInformation
{

	protected static $emptyData = [
		'clientName' => null,
		'clientPhone' => null,
		'clientAddress' => null,
		'clientAddress2' => null,
		'clientLocality' => null,
		'clientPostcode' => null,
		'clientPrimaryLocation' => null,
		'clientLanguage' => null,
		'clientCompanyName' => null,
		'clientCompanyId' => null,
		'clientCompanyVatId' => null,
	];


	public static function filter($info)
	{
		$info = array_merge(self::$emptyData, $info);
		foreach($info as $key => $value) {
			if(!array_key_exists($key, self::$emptyData)) {
				unset($info[$key]);
			}
		}
		return $info;
	}
}

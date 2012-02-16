<?php

namespace API;


use Tra\Services;
/**
* 
*/
class Api extends \Nette\Object {
	
	public static function registrationProcess($data) {
		$sUser = new Services\User;
		$sRental = new Services\Rental;

		$eUser = $sUser->create($data);

		$data->Rental->user = $eUser;
		$sRental->create($data);

		$sUser->sendEmail();

	}
}
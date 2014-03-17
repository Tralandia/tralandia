<?php
/**
 * This file is part of the Tralandia.
 * User: david
 * Created at: 8/5/13 2:16 PM
 */

namespace Tralandia\Rental;


use Nette;

class UnbanRentalListener implements \Kdyby\Events\Subscriber {

	/**
	 * @var BanListManager
	 */
	private $banListManager;


	public function __construct(BanListManager $banListManager)
	{
		$this->banListManager = $banListManager;
	}

	public function getSubscribedEvents()
	{
		return [
			'FormHandler\RegistrationHandler::onSuccess',
		];
	}


	public function onSuccess(\Entity\Rental\Rental $rental)
	{
		$this->banListManager->unbanRental($rental);
	}

}

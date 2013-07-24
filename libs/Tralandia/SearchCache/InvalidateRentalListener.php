<?php
/**
 * This file is part of the Tralandia.
 * User: david
 * Created at: 7/22/13 3:35 PM
 */

namespace Tralandia\Rental;


use Doctrine\ORM\EntityManager;
use Entity\Rental\Rental;
use Nette;

class InvalidateRentalListener implements \Kdyby\Events\Subscriber {

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	private $em;

	/**
	 * @var RankCalculator
	 */
	private $rankCalculator;


	public function __construct(RankCalculator $rankCalculator, EntityManager $em) {

		$this->rankCalculator = $rankCalculator;
		$this->em = $em;
	}

	public function getSubscribedEvents()
	{
		return [
			'FormHandler\RentalEditHandler::onSuccess',
		];
	}


	public function onSuccess(Rental $rental)
	{
		$this->rankCalculator->updateRank($rental);
		$this->em->flush($rental);
	}




}

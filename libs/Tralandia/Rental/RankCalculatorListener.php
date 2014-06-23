<?php
/**
 * This file is part of the Tralandia.
 * User: david
 * Created at: 7/22/13 3:35 PM
 */

namespace Tralandia\Rental;


use Doctrine\ORM\EntityManager;
use Nette;

class RankCalculatorListener implements \Kdyby\Events\Subscriber {

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
			'FormHandler\RegistrationHandler::onSuccess',
			'OwnerModule\RentalEdit\AboutForm::onFormSuccess' => 'onControlSubmit',
			'OwnerModule\RentalEdit\MediaForm::onFormSuccess' => 'onControlSubmit',
			'OwnerModule\RentalEdit\PricesForm::onFormSuccess' => 'onControlSubmit',
			'OwnerModule\RentalEdit\AmenitiesForm::onFormSuccess' => 'onControlSubmit',
			'OwnerModule\RentalEdit\InterviewForm::onFormSuccess' => 'onControlSubmit',
			'\Tralandia\Harvester\RegistrationData::onSuccess',
		];
	}

	public function onControlSubmit($form, $rental)
	{
		$this->onSuccess($rental);
	}

	public function onSuccess(\Entity\Rental\Rental $rental)
	{
		$this->rankCalculator->updateRank($rental);
		$this->em->flush($rental);
	}




}

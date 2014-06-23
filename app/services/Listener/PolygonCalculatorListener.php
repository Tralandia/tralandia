<?php
namespace Listener;

use Doctrine\ORM\EntityManager;
use Entity\Rental\Rental;
use Nette;
use Service\PolygonService;

class PolygonCalculatorListener implements \Kdyby\Events\Subscriber
{

	/**
	 * @var \Service\PolygonService
	 */
	protected $polygonCalculator;


	/**
	 * @param PolygonService $polygonCalculator
	 */
	public function __construct(PolygonService $polygonCalculator)
	{
		$this->polygonCalculator = $polygonCalculator;
	}


	/**
	 * @return array
	 */
	public function getSubscribedEvents()
	{
		return [
			'FormHandler\RegistrationHandler::onSuccess',
			'OwnerModule\RentalEdit\AboutForm::onFormSuccess' => 'onControlSubmit',
			'OwnerModule\RentalEdit\MediaForm::onFormSuccess' => 'onControlSubmit',
			'OwnerModule\RentalEdit\PricesForm::onFormSuccess' => 'onControlSubmit',
			'OwnerModule\RentalEdit\AmenitiesForm::onFormSuccess' => 'onControlSubmit',
			'OwnerModule\RentalEdit\InterviewForm::onFormSuccess' => 'onControlSubmit',
			'Tralandia\Harvester\RegistrationData::onRegister' => 'onSuccess',
		];
	}


	public function onControlSubmit($form, $rental)
	{
		$this->onSuccess($rental);
	}

	/**
	 * @param Rental $rental
	 */
	public function onSuccess(Rental $rental)
	{
		$this->polygonCalculator->setLocationsForRental($rental);
		$this->polygonCalculator->update($rental);
	}
}

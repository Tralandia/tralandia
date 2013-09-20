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
			'FormHandler\RentalEditHandler::onGpsChange' => 'onSuccess',
		];
	}


	/**
	 * @param Rental $rental
	 */
	public function onSuccess(Rental $rental)
	{
		$this->polygonCalculator->setLocationsForRental($rental);
		$this->polygonCalculator->update($rental);
		$this->polygonCalculator->update($rental->getAddress());
	}
}

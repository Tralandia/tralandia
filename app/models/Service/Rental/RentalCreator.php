<?php

namespace Service\Rental;

use Nette;
use Entity\User\User;
use Entity\Location\Location;
use Repository\Rental\RentalRepository;

/**
 * RentalCreator class
 *
 * @author Dávid Ďurika
 */
class RentalCreator
{

	/**
	 * @var \Repository\Rental\RentalRepository
	 */
	protected $rentalRepository;

	/**
	 * @param \Repository\Rental\RentalRepository $rentalRepository
	 */
	public function __construct(RentalRepository $rentalRepository)
	{
		$this->rentalRepository = $rentalRepository;
	}

	public function create(Location $location, User $user, $rentalName)
	{
		/** @var $rental \Entity\Rental\Rental */
		$rental = $this->rentalRepository->createNew();

		/** @var $address \Entity\Contact\Address */
		$address = $this->rentalRepository->related('address')->createNew();
		$address->setPrimaryLocation($location);
		$rental->setAddress($address);

		$user->addRental($rental);

		$rental->getName()->createTranslation($location->getDefaultLanguage(), $rentalName);

		return $rental;
	}

	public function setPrice(\Entity\Rental\Rental $rental, $price)
	{
		$currency = $rental->getPrimaryLocation()->getDefaultCurrency();
		$price = new \Extras\Types\Price($price, $currency);
		$rental->setPriceSeason($price);

		return $this;
	}

}

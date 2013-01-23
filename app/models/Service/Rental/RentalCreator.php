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

	public function create(\Entity\Contact\Address $address, User $user, $rentalName)
	{
		/** @var $rental \Entity\Rental\Rental */
		$rental = $this->rentalRepository->createNew();

		$rental->setAddress($address);

		$user->addRental($rental);

		$rental->getName()->createTranslation($address->getPrimaryLocation()->getDefaultLanguage(), $rentalName);

		return $rental;
	}

}

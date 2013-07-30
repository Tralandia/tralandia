<?php

namespace Service\Rental;

use Nette;
use Entity\User\User;
use Entity\Location\Location;
use Repository\Rental\RentalRepository;
use Service\Contact\AddressNormalizer;

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
	 * @var \Service\Contact\AddressNormalizer
	 */
	protected $addressNormalizer;

	/**
	 * @param \Repository\Rental\RentalRepository $rentalRepository
	 * @param \Service\Contact\AddressNormalizer $addressNormalizer
	 */
	public function __construct(RentalRepository $rentalRepository, AddressNormalizer $addressNormalizer)
	{
		$this->rentalRepository = $rentalRepository;
		$this->addressNormalizer = $addressNormalizer;
	}

	public function create(\Entity\Contact\Address $address, User $user, $rentalName)
	{
		$language = $user->getLanguage();

		/** @var $rental \Entity\Rental\Rental */
		$rental = $this->rentalRepository->createNew();

		$rental->setSlug($rentalName);

		$rental->getName()->setSourceLanguage($language);
		$rental->getTeaser()->setSourceLanguage($language);

		$answers = $rental->getInterviewAnswers();
		foreach($answers as $answer) {
			$answer->getAnswer()->setSourceLanguage($language);
		}

		$this->addressNormalizer->update($address, TRUE);
		$rental->setAddress($address);

		$user->addRental($rental);

		$rental->getName()->createTranslation($address->getPrimaryLocation()->getDefaultLanguage(), $rentalName);

		return $rental;
	}

}

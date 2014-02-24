<?php

namespace Service\Rental;

use Doctrine\ORM\EntityManager;
use Entity\Contact\Address;
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
	 * @var \Tralandia\BaseDao
	 */
	protected $interviewQuestionRepository;

	/**
	 * @var \Tralandia\BaseDao
	 */
	protected $interviewAnswerRepository;

	/**
	 * @var \Tralandia\BaseDao
	 */
	protected $addressRepository;

	/**
	 * @var \Service\Contact\AddressNormalizer
	 */
	protected $addressNormalizer;



	/**
	 * @param \Doctrine\ORM\EntityManager $em
	 * @param \Service\Contact\AddressNormalizer $addressNormalizer
	 */
	public function __construct(EntityManager $em, AddressNormalizer $addressNormalizer)
	{
		$this->rentalRepository = $em->getRepository(RENTAL_ENTITY);
		$this->languageRepository = $em->getRepository(LANGUAGE_ENTITY);
		$this->interviewQuestionRepository = $em->getRepository(INTERVIEW_QUESTION_ENTITY);
		$this->interviewAnswerRepository = $em->getRepository(INTERVIEW_ANSWER_ENTITY);
		$this->addressRepository = $em->getRepository(ADDRESS_ENTITY);
		$this->addressNormalizer = $addressNormalizer;
	}

	public function create(\Entity\Contact\Address $address, $userOrPrimaryLocation, $rentalName)
	{
		if ($userOrPrimaryLocation instanceof User){
			$language = $userOrPrimaryLocation->getLanguage();
		} else {
			$language = $userOrPrimaryLocation;
		}

		$this->addressNormalizer->update($address, TRUE);

		$user = NULL;
		if ($userOrPrimaryLocation instanceof User){
			$user = $userOrPrimaryLocation;
		}

		$rental = $this->createRental($user, $address, $rentalName, $language);

		return $rental;
	}


	/**
	 * @param User $user
	 * @param Location $primaryLocation
	 * @param $rentalName
	 *
	 * @return \Entity\Rental\Rental
	 */
	public function simpleCreate(User $user, Location $primaryLocation, $rentalName)
	{
		/** @var $address Address */
		$address = $this->addressRepository->createNew();

		$address->setPrimaryLocation($primaryLocation);

		return $this->createRental($user, $address, $rentalName, $user->getLanguage());
	}


	public function createRental(User $user = NULL, Address $address, $rentalName, $language)
	{
		/** @var $rental \Entity\Rental\Rental */
		$rental = $this->rentalRepository->createNew();

		$rentalName = \Tools::transliterate($rentalName);
		$rental->setSlug($rentalName);

		$rental->getName()->setSourceLanguage($language);
		$rental->getTeaser()->setSourceLanguage($language);

		if($translation = $rental->getName()->getTranslation($language)) {
			$translation->setTranslation($rentalName);
		} else {
			$rental->getName()->createTranslation($language, $rentalName);
		}

		$questions = $this->interviewQuestionRepository->findAll();
		/** @var $question \Entity\Rental\InterviewQuestion */
		foreach($questions as $question) {
			/** @var $answer \Entity\Rental\InterviewAnswer */
			$answer = $this->interviewAnswerRepository->createNew();
			$answer->getAnswer()->setSourceLanguage($language);
			$answer->setQuestion($question);
			$rental->addInterviewAnswer($answer);
		}

		$rental->setAddress($address);

		$user->addRental($rental);

		return $rental;
	}

}

<?php

namespace Service\Rental;

use Doctrine\ORM\EntityManager;
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
	 * @var \Repository\BaseRepository
	 */
	protected $interviewQuestionRepository;
	/**
	 * @var \Repository\BaseRepository
	 */
	protected $interviewAnswerRepository;

	/**
	 * @var \Service\Contact\AddressNormalizer
	 */
	protected $addressNormalizer;

	/**
	 * @var \Transliterator
	 */
	private $transliterator;


	/**
	 * @param \Doctrine\ORM\EntityManager $em
	 * @param \Service\Contact\AddressNormalizer $addressNormalizer
	 */
	public function __construct(EntityManager $em, AddressNormalizer $addressNormalizer, \Transliterator $transliterator)
	{
		$this->rentalRepository = $em->getRepository(RENTAL_ENTITY);
		$this->languageRepository = $em->getRepository(LANGUAGE_ENTITY);
		$this->interviewQuestionRepository = $em->getRepository(INTERVIEW_QUESTION_ENTITY);
		$this->interviewAnswerRepository = $em->getRepository(INTERVIEW_ANSWER_ENTITY);
		$this->addressNormalizer = $addressNormalizer;
		$this->transliterator = $transliterator;
	}

	public function create(\Entity\Contact\Address $address, $userOrPrimaryLocation, $rentalName)
	{
		if ($userOrPrimaryLocation instanceof User){
			$language = $userOrPrimaryLocation->getLanguage();
		} else {
			$language = $userOrPrimaryLocation;
		}

		/** @var $rental \Entity\Rental\Rental */
		$rental = $this->rentalRepository->createNew();

		$rentalName = $this->transliterator->transliterate($rentalName);
		$rental->setSlug($rentalName);

		$rental->getName()->setSourceLanguage($language);
		$rental->getTeaser()->setSourceLanguage($language);

		$questions = $this->interviewQuestionRepository->findAll();
		/** @var $question \Entity\Rental\InterviewQuestion */
		foreach($questions as $question) {
			/** @var $answer \Entity\Rental\InterviewAnswer */
			$answer = $this->interviewAnswerRepository->createNew();
			$answer->getAnswer()->setSourceLanguage($language);
			$answer->setQuestion($question);
			$rental->addInterviewAnswer($answer);
		}

		$this->addressNormalizer->update($address, TRUE);
		$rental->setAddress($address);
		if ($userOrPrimaryLocation instanceof User){
			$userOrPrimaryLocation->addRental($rental);
		}


		if($translation = $rental->getName()->getTranslation($language)) {
			$translation->setTranslation($rentalName);
		} else {
			$rental->getName()->createTranslation($language, $rentalName);
		}

		return $rental;
	}

}

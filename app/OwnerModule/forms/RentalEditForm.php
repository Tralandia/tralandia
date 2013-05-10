<?php

namespace OwnerModule\Forms;

use Doctrine\ORM\EntityManager;
use Entity\Rental\Rental;
use Environment\Collator;
use Environment\Environment;
use Extras\Forms\Container\IRentalContainerFactory;
use Nette;
use Nette\Localization\ITranslator;
use Entity\Location\Location;
use Repository\Location\LocationRepository;
use Repository\LanguageRepository;

/**
 * RegistrationForm class
 *
 * @author Dávid Ďurika
 */
class RentalEditForm extends \FrontModule\Forms\BaseForm
{

	/**
	 * @var \Entity\Rental\Rental
	 */
	protected $rental;

	/**
	 * @var \Environment\Environment
	 */
	protected $environment;

	/**
	 * @var \Entity\Location\Location
	 */
	protected $country;

	/**
	 * @var \Environment\Collator
	 */
	protected $collator;

	/**
	 * @var IRentalContainerFactory
	 */
	protected $rentalContainerFactory;

	/**
	 * @var \Repository\Location\LocationRepository
	 */
	protected $locationRepository;

	/**
	 * @var \Repository\LanguageRepository
	 */
	protected $languageRepository;

	/**
	 * @var \Repository\User\UserRepository
	 */
	protected $userRepository;

	/**
	 * @var \Repository\InterviewQuestionRepository
	 */
	protected $interviewQuestionRepository;

	/**
	 * @var \Repository\Rental\AmenityRepository
	 */
	protected $amenityRepository;

	protected $phraseRepository;


	/**
	 * @param \Entity\Rental\Rental $rental
	 * @param \Environment\Environment $environment
	 * @param IRentalContainerFactory $rentalContainerFactory
	 * @param EntityManager $em
	 * @param ITranslator $translator
	 */
	public function __construct(Rental $rental, Environment $environment, IRentalContainerFactory $rentalContainerFactory,
								EntityManager $em, ITranslator $translator)
	{
		$this->rental = $rental;
		$this->environment = $environment;
		$this->country = $environment->getPrimaryLocation();
		$this->collator = $environment->getLocale()->getCollator();
		$this->rentalContainerFactory = $rentalContainerFactory;

		$this->locationRepository = $em->getRepository(LOCATION_ENTITY);
		$this->languageRepository = $em->getRepository(LANGUAGE_ENTITY);
		$this->userRepository = $em->getRepository(USER_ENTITY);
		$this->interviewQuestionRepository = $em->getRepository(INTERVIEW_QUESTION_ENTITY);
		$this->amenityRepository = $em->getRepository(RENTAL_AMENITY_ENTITY);
		$this->phraseRepository = $em->getRepository(PHRASE_ENTITY);
		parent::__construct($translator);
	}


	public function buildForm()
	{
		$phonePrefixes = $this->locationRepository->getCountriesPhonePrefixes();
		$supportedLanguages = $this->languageRepository->getSupportedSortedByName($this->translator, $this->collator);
		$supportedLanguagesForSelect = $this->languageRepository->getSupportedSortedByName($this->translator, $this->collator);
		$questions = $this->interviewQuestionRepository->findAll();
		$currency = $this->country->getDefaultCurrency();

//		$this->addText('name', 'o100070')
//			->setOption('help', $this->translate('o100071'))
//	        //->addRule(Form::MAX_LENGTH, 'o100101', 70);
//			;

		$rental = $this->rental;
		$rentalContainer = $this->rentalContainerFactory->create($this->environment, $this->rental);
		$this['rental'] = $rentalContainer;

		$rentalContainer->addRentalPriceListContainer('priceList', $currency);
		$rentalContainer->addRentalPriceUploadContainer('priceUpload', $rental);

		$rentalContainer->addPhoneContainer('phone', 'o10899', $phonePrefixes);

		$rentalContainer->addText('url', 'o977')
			->setOption('help', $this->translate('o978'))
			->setOption('prepend', 'http://')
			->addRule(self::URL, $this->translate('o100102'));

		$rentalContainer->addText('price', 'o100078')
			->setOption('append', $currency->getIso() . ' ' . $this->translate('o100004'))
			->setOption('help', $this->translate('o100073'))
			->addRule(self::RANGE, $this->translate('o100105'), [0, 999999999999999]);

		$languages = array();

		foreach($supportedLanguagesForSelect as $language){
			$languages[$language->getIso()] = $this->translate($language->getName());
		}

		$rentalContainer->addSelect('translationLanguage', '##', $languages)
						->setDefaultValue($this->environment->getLanguage()->getIso());

		$nameContainer = $rentalContainer->addContainer('name');
		$teaserContainer = $rentalContainer->addContainer('teaser');
		$interviewContainer = $rentalContainer->addContainer('interview');

		foreach($questions as $question) {
			$interviewContainer->addContainer($question->getId());
		}

		$namePhrase = $this->phraseRepository->findOneByOldId('886');
		$teaserPhrase = $this->phraseRepository->findOneByOldId('890');
		foreach($supportedLanguages as $language) {
			$iso = $language->getIso();
			$nameContainer->addText($iso, $namePhrase->getTranslationText($language))
				->setOption('prepend', $iso)
				->setOption('help', $this->translate('o100071'));
				// ->addRule(self::LENGTH, $this->translate('o100101'), [2, 70]);

			$teaserContainer->addText($iso, $teaserPhrase->getTranslationText($language))
				->setOption('prepend', $iso)
				->setOption('help', '');
			$i = 1;
			foreach($questions as $question) {
				$interviewContainer[$question->getId()]->addTextArea($iso, $i.'. '.$question->getQuestion()->getTranslationText($language));
				++$i;
			}
		}

		

		$rentalContainer->addText('bedroomCount', $this->translate('o100075'));

		$rentalContainer->addText('roomsLayout', $this->translate('o100190'));

		$amenities = $this->amenityRepository->findByChildrenTypeForSelect($this->getTranslator(), $this->collator);
		$rentalContainer->addMultiOptionList('children', 'o100169', $amenities);

		$amenities = $this->amenityRepository->findByActivityTypeForSelect($this->getTranslator(), $this->collator);
		$rentalContainer->addMultiOptionList('activity', '#activity', $amenities);

		$amenities = $this->amenityRepository->findByRelaxTypeForSelect($this->getTranslator(), $this->collator);
		$rentalContainer->addMultiOptionList('relax', 'o100170', $amenities);

		$amenities = $this->amenityRepository->findByServiceTypeForSelect($this->getTranslator(), $this->collator);
		$rentalContainer->addMultiOptionList('service', 'o100171', $amenities);

		$amenities = $this->amenityRepository->findByWellnessTypeForSelect($this->getTranslator(), $this->collator);
		$rentalContainer->addMultiOptionList('wellness', 'o100172', $amenities);

		$amenities = $this->amenityRepository->findByCongressTypeForSelect($this->getTranslator(), $this->collator);
		$rentalContainer->addMultiOptionList('congress', 'o100173', $amenities);

		$amenities = $this->amenityRepository->findByKitchenTypeForSelect($this->getTranslator(), $this->collator);
		$rentalContainer->addMultiOptionList('kitchen', 'o100174', $amenities);

		$amenities = $this->amenityRepository->findByBathroomTypeForSelect($this->getTranslator(), $this->collator);
		$rentalContainer->addMultiOptionList('bathroom', 'o100175', $amenities);

		$amenities = $this->amenityRepository->findByHeatingTypeForSelect($this->getTranslator(), $this->collator);
		$rentalContainer->addMultiOptionList('heating', 'o100177', $amenities);

		$amenities = $this->amenityRepository->findByParkingTypeForSelect($this->getTranslator(), $this->collator);
		$rentalContainer->addMultiOptionList('parking', 'o100178', $amenities);

		$amenities = $this->amenityRepository->findByRoomTypeForSelect($this->getTranslator(), $this->collator);
		$rentalContainer->addMultiOptionList('room', 'o100176', $amenities);

		$amenities = $this->amenityRepository->findByOtherTypeForSelect($this->getTranslator(), $this->collator);
		$rentalContainer->addMultiOptionList('other', 'o100179', $amenities);

		$this->addSubmit('submit', 'o100083');

		$this->onValidate[] = callback($this, 'validation');
		$this->onValidate[] = $rentalContainer->validation;
	}


	public function setDefaultsValues()
	{
		$rental = $this->rental;

		$placement = 0;
		foreach ($rental->getPlacements() as $place) {
			$placement = $place->getId();
			break;
		}
		$pet = $rental->getPetAmenity();

		$name = [];
		foreach ($rental->name->getTranslations() as $translation) {
			$language = $translation->getLanguage();
			$name[$language->iso] = $translation->translation;
		}

		$teaser = [];
		foreach ($rental->teaser->getTranslations() as $translation) {
			$language = $translation->getLanguage();
			$teaser[$language->iso] = $translation->translation;
		}

		$interview = [];
		foreach ($rental->interviewAnswers as $answer) {
			$question = $answer->getQuestion();
			$interview[$question->id] = [];
			foreach ($answer->answer->getTranslations() as $translation) {
				$language = $translation->language;
				$interview[$question->id][$language->iso] = $translation->translation;
			}
		}

		$defaults = [
			'email' => $rental->getEmail(),
			'url' => $rental->getUrl(),
			'phone' => $rental->getPhone(),
			'rental' => [
				'name' => $name,
				'teaser' => $teaser,
				'price' => $rental->getPrice()->getSourceAmount(),
				'maxCapacity' => $rental->getMaxCapacity(),
				'interview' => $interview,
				'bedroomCount' => $rental->bedroomCount,
				'roomsLayout' => $rental->rooms,
				'separateGroups' => $rental->getSeparateGroups(),
 				'type' => [
					'type' => $rental->getType()->getId(),
					'classification' => $rental->getClassification(),
				],
				'checkIn' => $rental->getCheckIn(),
				'checkOut' => $rental->getCheckOut(),
				'ownerAvailability' => $rental->getOwnerAvailability()->getId(),
				'pet' => ($pet ? $pet->getId() : NULL),
				'placement' => $placement,
				'address' => $rental->getAddress(),

				'important' => array_map(function ($a) {
						return $a->getId();
					}, $rental->getImportantAmenities()
				),
				'board' => array_map(function ($a) {
						return $a->getId();
					}, $rental->getBoardAmenities()
				),
				'children' => array_map(function ($a) {
						return $a->getId();
					}, $rental->getChildrenAmenities()
				),
				'activity' => array_map(function ($a) {
						return $a->getId();
					}, $rental->getActivityAmenities()
				),
				'relax' => array_map(function ($a) {
						return $a->getId();
					}, $rental->getRelaxAmenities()
				),
				'service' => array_map(function ($a) {
						return $a->getId();
					}, $rental->getServiceAmenities()
				),
				'wellness' => array_map(function ($a) {
						return $a->getId();
					}, $rental->getWellnessAmenities()
				),
				'congress' => array_map(function ($a) {
						return $a->getId();
					}, $rental->getCongressAmenities()
				),
				'kitchen' => array_map(function ($a) {
						return $a->getId();
					}, $rental->getKitchenAmenities()
				),
				'bathroom' => array_map(function ($a) {
						return $a->getId();
					}, $rental->getBathroomAmenities()
				),
				'heating' => array_map(function ($a) {
						return $a->getId();
					}, $rental->getHeatingAmenities()
				),
				'parking' => array_map(function ($a) {
						return $a->getId();
					}, $rental->getParkingAmenities()
				),
				'room' => array_map(function ($a) {
						return $a->getId();
					}, $rental->getRoomAmenities()
				),
				'other' => array_map(function ($a) {
						return $a->getId();
					}, $rental->getOtherAmenities()
				)
			]
		];

		$this->setDefaults($defaults);
	}


	public function validation(RentalEditForm $form)
	{
		$values = $form->getValues()->rental;
		d($values);

	}

}


interface IRentalEditFormFactory
{

	/**
	 * @param \Entity\Rental\Rental $rental
	 * @param \Environment\Environment $environment
	 *
	 * @return RentalEditForm
	 */
	public function create(Rental $rental, Environment $environment);
}


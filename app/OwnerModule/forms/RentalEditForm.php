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

	protected $interviewQuestionRepository;


	/**
	 * @var \Repository\Rental\AmenityRepository
	 */
	protected $amenityRepository;


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
		parent::__construct($translator);
	}


	public function buildForm()
	{
		$phonePrefixes = $this->locationRepository->getCountriesPhonePrefixes();
		$supportedLanguages = $this->languageRepository->getSupportedSortedByName();
		$supportedLanguagesForSelect = $this->languageRepository->getSupportedSortedByName($this->translator, $this->collator);
		$questions = $this->interviewQuestionRepository->findAll();

//		$this->addText('name', 'o100070')
//			->setOption('help', $this->translate('o100071'))
//	        //->addRule(Form::MAX_LENGTH, 'o100101', 70);
//			;
//
		$rental = $this->rental;
		$rentalContainer = $this->rentalContainerFactory->create($this->environment, $this->rental);
		$this['rental'] = $rentalContainer;

		$rentalContainer->addRentalPriceListContainer('priceList');
		$rentalContainer->addRentalPriceUploadContainer('priceUpload', $rental);

		$rentalContainer->addPhoneContainer('phone', 'o10899', $phonePrefixes);


		$rentalContainer->addText('url', 'o977')
			->setOption('help', $this->translate('o978'))
			->setOption('prepend', 'http://')
			->addRule(self::URL, $this->translate('o100102'));
		;



		$currency = $this->country->getDefaultCurrency();
		$rentalContainer->addText('price', 'o100078')
			->setOption('append', $currency->getIso() . ' ' . $this->translate('o100004'))
			->setOption('help', $this->translate('o100073'))
			->addRule(self::INTEGER, $this->translate('o100105'))
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

		foreach($supportedLanguages as $language) {
			$iso = $language->getIso();
			$nameContainer->addText($iso, 'o886')
							->setOption('prepend', $iso)
							->setOption('help', $this->translate('o100071'))
							->addRule(self::LENGTH, $this->translate('o100101'), [2, 70]);

			$teaserContainer->addText($iso, 'o890')
							->setOption('prepend', $iso)
							->setOption('help', $iso);
			$i = 1;
			foreach($questions as $question) {
				$interviewContainer[$question->getId()]->addTextArea($iso, $i.'. '.$question->getQuestion()->getTranslationText($language));
				++$i;
			}
		}

		$rentalContainer->addText('bedroomCount', '#bedroomCount');

		$rentalContainer->addText('roomsLayout', '#rooms layout');

		$amenities = $this->amenityRepository->findByChildrenTypeForSelect($this->getTranslator(), $this->collator);
		$rentalContainer->addMultiOptionList('children', 'o100169', $amenities)
			->addRule(self::FILLED, $this->translate('o100109'));

		$amenities = $this->amenityRepository->findByActivityTypeForSelect($this->getTranslator(), $this->collator);
		$rentalContainer->addMultiOptionList('activity', '#activity', $amenities)
			->addRule(self::FILLED, $this->translate('o100109'));

		$amenities = $this->amenityRepository->findByRelaxTypeForSelect($this->getTranslator(), $this->collator);
		$rentalContainer->addMultiOptionList('relax', 'o100170', $amenities)
			->addRule(self::FILLED, $this->translate('o100109'));

		$amenities = $this->amenityRepository->findByServiceTypeForSelect($this->getTranslator(), $this->collator);
		$rentalContainer->addMultiOptionList('service', 'o100171', $amenities)
			->addRule(self::FILLED, $this->translate('o100109'));

		$amenities = $this->amenityRepository->findByWellnessTypeForSelect($this->getTranslator(), $this->collator);
		$rentalContainer->addMultiOptionList('wellness', 'o100172', $amenities)
			->addRule(self::FILLED, $this->translate('o100109'));

		$amenities = $this->amenityRepository->findByCongressTypeForSelect($this->getTranslator(), $this->collator);
		$rentalContainer->addMultiOptionList('congress', 'o100173', $amenities)
			->addRule(self::FILLED, $this->translate('o100109'));

		$amenities = $this->amenityRepository->findByKitchenTypeForSelect($this->getTranslator(), $this->collator);
		$rentalContainer->addMultiOptionList('kitchen', 'o100174', $amenities)
			->addRule(self::FILLED, $this->translate('o100109'));

		$amenities = $this->amenityRepository->findByBathroomTypeForSelect($this->getTranslator(), $this->collator);
		$rentalContainer->addMultiOptionList('bathroom', 'o100175', $amenities)
			->addRule(self::FILLED, $this->translate('o100109'));

		$amenities = $this->amenityRepository->findByHeatingTypeForSelect($this->getTranslator(), $this->collator);
		$rentalContainer->addMultiOptionList('heating', 'o100177', $amenities)
			->addRule(self::FILLED, $this->translate('o100109'));

		$amenities = $this->amenityRepository->findByParkingTypeForSelect($this->getTranslator(), $this->collator);
		$rentalContainer->addMultiOptionList('parking', 'o100178', $amenities)
			->addRule(self::FILLED, $this->translate('o100109'));

		$amenities = $this->amenityRepository->findByRoomTypeForSelect($this->getTranslator(), $this->collator);
		$rentalContainer->addMultiOptionList('room', 'o100176', $amenities)
			->addRule(self::FILLED, $this->translate('o100109'));

		$amenities = $this->amenityRepository->findByOtherTypeForSelect($this->getTranslator(), $this->collator);
		$rentalContainer->addMultiOptionList('other', 'o100179', $amenities)
			->addRule(self::FILLED, $this->translate('o100109'));

		$this->addSubmit('submit', 'o100083');

		$this->onValidate[] = callback($this, 'validation');
		$this->onValidate[] = $rentalContainer->validation;
	}


	public function setDefaultsValues()
	{
		$rental = $this->rental;
		$places = [];
		foreach ($rental->getPlacements() as $place) {
			$places[] = $place->getId();
		}
		$pet = $rental->getPetAmenity();
		$defaults = [
			//'referrer' => 'luzbo',
			'email' => $rental->getEmail(),
			'url' => $rental->getUrl(),

			'phone' => $rental->getPhone(),
			'rental' => [
				'price' => $rental->getPrice()->getSourceAmount(),
				'maxCapacity' => $rental->getMaxCapacity(),
				'type' => [
					'type' => $rental->getType()->getId(),
					'classification' => $rental->getClassification(),
				],
				'board' => array_map(function ($a) {
					return $a->getId();
				}, $rental->getBoardAmenities()),
				'important' => array_map(function ($a) {
					return $a->getId();
				}, $rental->getImportantAmenities()),
				'ownerAvailability' => $rental->getOwnerAvailability()->getId(),
				'pet' => $pet ? $pet->getId() : NULL,
				'placement' => $places,

				'address' => $rental->getAddress(),
			],
		];

		$this->setDefaults($defaults);
	}


	public function validation(RegistrationForm $form)
	{
		$values = $form->getValues();

		$email = $values->email;
		if ($email && !$form['email']->hasErrors()) {
			$emailIsOccupied = $this->userRepository->findOneByLogin($email);
			if ($emailIsOccupied) {
				$form['email']->addError($this->translate('o852'));
			}
		}
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


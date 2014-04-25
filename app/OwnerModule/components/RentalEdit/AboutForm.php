<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 11/04/14 08:13
 */

namespace OwnerModule\RentalEdit;


use BaseModule\Forms\BaseForm;
use BaseModule\Forms\ISimpleFormFactory;
use Entity\Rental\Rental;
use Environment\Environment;
use Kdyby\Doctrine\EntityManager;
use Nette;
use Tralandia\BaseDao;
use Tralandia\Dictionary\PhraseManager;
use Tralandia\Language\Languages;
use Tralandia\Location\Countries;
use Tralandia\Placement\Placements;
use Tralandia\Rental\Amenities;
use Tralandia\Rental\Types;

class AboutForm extends BaseFormControl
{

	/**
	 * @var \Entity\Rental\Rental
	 */
	private $rental;

	/**
	 * @var \BaseModule\Forms\ISimpleFormFactory
	 */
	private $formFactory;

	/**
	 * @var \Tralandia\Location\Countries
	 */
	private $countries;

	/**
	 * @var \Tralandia\Language\Languages
	 */
	private $languages;

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
	 * @var BaseDao
	 */
	protected $phraseRepository;

	/**
	 * @var BaseDao
	 */
	protected $interviewQuestionRepository;

	/**
	 * @var \Tralandia\Placement\Placements
	 */
	private $placements;

	/**
	 * @var \Tralandia\Rental\Types
	 */
	private $rentalTypes;

	/**
	 * @var \Tralandia\Rental\Amenities
	 */
	private $amenities;

	/**
	 * @var \Kdyby\Doctrine\EntityManager
	 */
	private $em;

	/**
	 * @var \Environment\Environment
	 */
	private $environment;

	/**
	 * @var \Tralandia\Dictionary\PhraseManager
	 */
	private $phraseManager;


	public function __construct(Rental $rental, Environment $environment, ISimpleFormFactory $formFactory, Countries $countries,
								Languages $languages, Placements $placements, Types $rentalTypes,
								Amenities $amenities, PhraseManager $phraseManager, EntityManager $em)
	{
		parent::__construct();
		$this->rental = $rental;
		$this->formFactory = $formFactory;
		$this->countries = $countries;
		$this->languages = $languages;
		$this->placements = $placements;
		$this->rentalTypes = $rentalTypes;
		$this->amenities = $amenities;
		$this->environment = $environment;
		$this->phraseManager = $phraseManager;

		$this->locationRepository = $em->getRepository(LOCATION_ENTITY);
		$this->languageRepository = $em->getRepository(LANGUAGE_ENTITY);
		$this->userRepository = $em->getRepository(USER_ENTITY);
		$this->interviewQuestionRepository = $em->getRepository(INTERVIEW_QUESTION_ENTITY);
		$this->phraseRepository = $em->getRepository(PHRASE_ENTITY);
		$this->em = $em;
	}


	public function createComponentForm()
	{
		$form = $this->formFactory->create();


		$phonePrefixes = $this->countries->getPhonePrefixes();
		$centralLanguage = $this->languageRepository->find(CENTRAL_LANGUAGE);
		$importantLanguages = $this->rental->getPrimaryLocation()->getImportantLanguages($centralLanguage);
		$importantLanguagesForSelect = [];
		foreach($importantLanguages as $language) {
			$importantLanguagesForSelect[$language->getId()] = $this->translate($language->getName());
		}
		$supportedLanguagesForSelect = $this->languages->getSupportedSortedByName();
		$questions = $this->interviewQuestionRepository->findAll();


		$form->addAddressContainer('address', $this->rental->getAddress());


		$placement = $this->placements->getForSelect();
		$form->addSelect('placement', 'o100143', $placement)
			->setOption('help', $this->translate('152270'))
			->setRequired($this->translate('152270'))
			->setPrompt('o854');

		$rentalTypes = $this->rentalTypes->getForSelect();
		$form->addRentalTypeContainer('type', $this->rental, $rentalTypes);

		$check = \Tools::$checkInOutOption;

		$form->addSelect('checkIn', 'o1586', $check)
			->setOption('help', $this->translate('151889'))
			->setRequired($this->translate('151889'))
			->setPrompt('o854');

		$form->addSelect('checkOut', 'o1588', $check)
			->setOption('help', $this->translate('151890'))
			->setRequired($this->translate('151889'))
			->setPrompt('o854');


		$form->addText('maxCapacity', 'o100072')
			//->addRule(self::RANGE, $this->translate('o100074'), [1, 1000])
			->setOption('help', $this->translate('o100073'))
			->setOption('append', $this->translate('o490', 2))
			->setRequired()
			->addRule(BaseForm::INTEGER, $this->translate('o100106'))
			->addRule(BaseForm::RANGE, $this->translate('o100106'), [0, 999999999999999]);



		$form->addPhoneContainer('phone', 'o10899', $phonePrefixes);


		$form->addText('url', 'o977')
			->setOption('help', $this->translate('o978'))
			->setOption('prepend', 'http://');

		$form->addText('contactName', '151894')
			->setOption('prepend', '<i class="icon-user"></i>')
			->setOption('help', $this->translate('151895'))
			->addRule(BaseForm::MIN_LENGTH, $this->translate('151895'), 2);

		$form->addText('email', 'o1096')
			->setOption('prepend', '<i class="icon-envelope"></i>')
			->setOption('help', $this->translate('o3095'))
			->addRule(BaseForm::EMAIL, $this->translate('o3095'));


		$languages = array($this->translate('153133'));

		foreach($supportedLanguagesForSelect as $language){
			$languages[$language->getIso()] = $this->translate($language->getName());
		}

		$nameContainer = $form->addContainer('name');
		$teaserContainer = $form->addContainer('teaser');
		$descriptionContainer = $form->addContainer('description');
		$interviewContainer = $form->addContainer('interview');

		foreach($questions as $question) {
			$interviewContainer->addContainer($question->getId());
		}

		foreach($importantLanguages as $language) {
			$iso = $language->getIso();

			$nameContainer->addText($iso, $this->translate('152275', null, null, null, $language))
				->setOption('help', $this->translate('o100071', null, null, null, $language))
				->addRule(BaseForm::MAX_LENGTH, $this->translate('o100101'), 70);

			$teaserContainer->addText($iso, $this->translate('152276', null, null, null, $language))
				->setOption('help', $this->translate('726', null, null, null, $language));

			$descriptionContainer->addTextArea($iso, $this->translate('696729', null, null, null, $language))
				->setAttribute('rows', 10)
				->setOption('help', '');

			$i = 1;
			foreach($questions as $question) {
				$interviewContainer[$question->getId()]->addTextArea($iso, $i.'. '.$question->getQuestion()->getTranslationText($language));
				++$i;
			}
		}

		$form->addMultiOptionList('spokenLanguages', $this->translate('13137'), $importantLanguagesForSelect);

		$form->addText('bedroomCount', $this->translate('o100075'))
			->setRequired($this->translate('1257'))
			->addRule(BaseForm::NUMERIC, $this->translate('1257'));

		$form->addText('roomsLayout', $this->translate('o100190'))
			->setOption('help', $this->translate('152269'));


		$amenityPets = $this->amenities->findByAnimalTypeForSelect();
		$form->addSelect('pet', 'o100079', $amenityPets)
			->setPrompt('o854')
			->setRequired($this->translate('o100108'))
			//->setOption('help', $this->translate('o5956'))
		;

		$amenityAvailability = $this->amenities->findByAvailabilityTypeForSelect();
		$form->addSelect('ownerAvailability', 'o100082', $amenityAvailability)
			->setPrompt('o854')
			->setRequired($this->translate('o100107'));
		//->setOption('help', $this->translate('o5956'))
		;

		$form->addSubmit('submit', 'o100083');

		$form->onSuccess[] = $this->processForm;

		$this->setDefaults($form);

		return $form;
	}


	public function setDefaults(BaseForm $form)
	{
		$rental = $this->rental;

		$placement = NULL;
		foreach ($rental->getPlacements() as $place) {
			$placement = $place->getId();
			break;
		}
		$pet = $rental->getPetAmenity();

		$name = [];
		foreach ($rental->getName()->getTranslations() as $translation) {
			$language = $translation->getLanguage();
			$name[$language->getIso()] = $translation->getTranslation();
		}

		$teaser = [];
		foreach ($rental->getTeaser()->getTranslations() as $translation) {
			$language = $translation->getLanguage();
			$teaser[$language->getIso()] = $translation->getTranslation();
		}

		$description = [];
		foreach ($rental->getDescription()->getTranslations() as $translation) {
			$language = $translation->getLanguage();
			$description[$language->getIso()] = $translation->getTranslation();
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

		$ownerAvailability = $rental->getOwnerAvailability();

		$spokenLanguages = $rental->getSpokenLanguages()->toArray();
		$spokenLanguages = \Tools::entitiesMap($spokenLanguages, 'id', 'id');

		$defaults = [
			'url' => $this->rental->getUrlWithoutProtocol(),
			'phone' => $this->rental->getPhone(),
			'contactName' => $this->rental->getContactName(),
			'email' => $this->rental->getEmail(),
			'name' => $name,
			'teaser' => $teaser,
			'description' => $description,
			'price' => $rental->getCurrency(),
			'maxCapacity' => $rental->getMaxCapacity(),
			'interview' => $interview,
			'bedroomCount' => $rental->bedroomCount,
			'roomsLayout' => $rental->rooms,
			'checkIn' => $rental->getCheckIn() ? : NULL,
			'checkOut' => $rental->getCheckOut() ? : NULL,
			'ownerAvailability' => $ownerAvailability ? $ownerAvailability->getId() : NULL,
			'pet' => ($pet ? $pet->getId() : NULL),
			'placement' => $placement,
			'spokenLanguages' => $spokenLanguages,
		];

		$form->setDefaults($defaults);
	}


	public function processForm(BaseForm $form)
	{
		$validValues = $form->getFormattedValues();
		$rental = $this->rental;

		if($value = $validValues['spokenLanguages']) {
			$spokenLanguages = $this->languages->findByIds($value);
			$rental->setSpokenLanguages($spokenLanguages);
		}

		if ($value = $validValues['address']) {
			$address = $value;
			if ($address['addressEntity']) {
				$rental->address = $address['addressEntity'];
			}
		}

		if ($value = $validValues['placement']) {
			$placementRepository = $this->em->getRepository(RENTAL_PLACEMENT_ENTITY);
			$placementEntity = $placementRepository->find($value);
			$rental->setPlacement($placementEntity);
		}

		if ($value = $validValues['type']) {
			$rental->type = $value->type;
			$rental->classification = $value->classification;
		}

		if ($value = $validValues['phone']) {
			if ($phoneEntity = $validValues['phone']->entity) {
				$rental->setPhone($phoneEntity);
			}
		}

		$rentalInfo = ['name', 'teaser', 'description'];
		foreach ($rentalInfo as $infoName) {
			if($value = $validValues[$infoName]) {
				$phrase = $rental->{$infoName};
				$translationsVariations = [];
				foreach ($value as $languageIso => $val) {
					$translationsVariations[$languageIso] = $val;
				}
				$this->phraseManager->updateTranslations($phrase, $translationsVariations);
			}
		}

		if ($value = $validValues['pet']) {
			$petAmenity = $this->amenities->findByPetType();

			foreach ($petAmenity as $amenity) {
				if ($value && $amenity->id == $value) {
					$rental->addAmenity($amenity);
				} else {
					$rental->removeAmenity($amenity);
				}
			}
		}

		if ($value = $validValues['ownerAvailability']) {
			$availabilityAmenities = $this->amenities->findByOwnerAvailabilityType();
			foreach ($availabilityAmenities as $amenity) {
				if ($amenity->getId() == $value) {
					$rental->addAmenity($amenity);
				} else {
					$rental->removeAmenity($amenity);
				}
			}
		}

		$value = $validValues['url'];
		$rental->setUrl($value);

		if ($value = $validValues['bedroomCount']) {
			$rental->bedroomCount = $value;
		}

		$simpleValues = ['checkIn', 'checkOut', 'maxCapacity', 'contactName', 'email'];
		foreach ($simpleValues as $valueName) {
			if ($value = $validValues[$valueName]) {
				$rental->{$valueName} = $value;
			}
		}

		$rental->rooms = $validValues['roomsLayout'];

		$this->em->persist($rental);
		$this->em->flush();

	}


}


interface IAboutFormFactory
{
	public function create(\Entity\Rental\Rental $rental);
}

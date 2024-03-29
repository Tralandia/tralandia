<?php

namespace OwnerModule\Forms;

use Doctrine\ORM\EntityManager;
use Entity\ImportantLanguageForLocation;
use Entity\Rental\Rental;
use Environment\Collator;
use Environment\Environment;
use Extras\Forms\Container\IRentalContainerFactory;
use Nette;
use Nette\Localization\ITranslator;
use Entity\Location\Location;
use Repository\Location\LocationRepository;
use Repository\LanguageRepository;
use Tralandia\Rental\Amenities;
use Tralandia\Language\Languages;
use Tralandia\Location\Countries;

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
	 * @var \Extras\Forms\Container\IRentalContainerFactory;
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


	protected $phraseRepository;

	/**
	 * @var \Tralandia\Location\Countries
	 */
	private $countries;

	/**
	 * @var \Tralandia\Language\Languages
	 */
	private $languages;

	/**
	 * @var \Tralandia\Rental\Amenities
	 */
	private $amenities;


	/**
	 * @param \Entity\Rental\Rental $rental
	 * @param \Environment\Environment $environment
	 * @param IRentalContainerFactory $rentalContainerFactory
	 * @param \Tralandia\Location\Countries $countries
	 * @param \Tralandia\Language\Languages $languages
	 * @param \Tralandia\Rental\Amenities $amenities
	 * @param EntityManager $em
	 * @param ITranslator $translator
	 */
	public function __construct(Rental $rental, Environment $environment, IRentalContainerFactory $rentalContainerFactory,
								Countries $countries, Languages $languages, Amenities $amenities,
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
		$this->phraseRepository = $em->getRepository(PHRASE_ENTITY);
		$this->countries = $countries;
		$this->languages = $languages;
		$this->amenities = $amenities;
		parent::__construct($translator);
	}


	public function buildForm()
	{
		$phonePrefixes = $this->countries->getPhonePrefixes();
		$centralLanguage = $this->languageRepository->find(CENTRAL_LANGUAGE);
		$importantLanguages = $this->rental->getPrimaryLocation()->getImportantLanguages($centralLanguage);
		$importantLanguagesForSelect = [];
		foreach($importantLanguages as $language) {
			$importantLanguagesForSelect[$language->getId()] = $this->translate($language->getName());
		}
		$supportedLanguagesForSelect = $this->languages->getSupportedSortedByName();
		$questions = $this->interviewQuestionRepository->findAll();

//		$this->addText('name', 'o100070')
//			->setOption('help', $this->translate('o100071'))
//	        //->addRule(Form::MAX_LENGTH, 'o100101', 70);
//			;

		$rental = $this->rental;
		$rentalContainer = $this->rentalContainerFactory->create($this->environment, $this->rental);
		$this['rental'] = $rentalContainer;


		$rentalContainer->addPhoneContainer('phone', 'o10899', $phonePrefixes);


		$rentalContainer->addText('url', 'o977')
			->setOption('help', $this->translate('o978'))
			->setOption('prepend', 'http://');

		$rentalContainer->addText('contactName', '151894')
			->setOption('prepend', '<i class="icon-user"></i>')
			->setOption('help', $this->translate('151895'))
			->addRule(self::MIN_LENGTH, $this->translate('151895'), 2);

		$rentalContainer->addText('email', 'o1096')
			->setOption('prepend', '<i class="icon-envelope"></i>')
			->setOption('help', $this->translate('o3095'))
			->addRule(self::EMAIL, $this->translate('o3095'));


		$languages = array($this->translate('153133'));

		foreach($supportedLanguagesForSelect as $language){
			$languages[$language->getIso()] = $this->translate($language->getName());
		}

		$nameContainer = $rentalContainer->addContainer('name');
		$teaserContainer = $rentalContainer->addContainer('teaser');
		$descriptionContainer = $rentalContainer->addContainer('description');
		$interviewContainer = $rentalContainer->addContainer('interview');

		foreach($questions as $question) {
			$interviewContainer->addContainer($question->getId());
		}

		foreach($importantLanguages as $language) {
			$iso = $language->getIso();

			$nameContainer->addText($iso, $this->translate('152275', null, null, null, $language))
				->setOption('help', $this->translate('o100071', null, null, null, $language))
				 ->addRule(self::MAX_LENGTH, $this->translate('o100101'), 70);

			$teaserContainer->addText($iso, $this->translate('152276', null, null, null, $language))
				->setOption('help', $this->translate('726', null, null, null, $language));

			$descriptionContainer->addTextArea($iso, $this->translate('696729', null, null, null, $language))
				->setOption('help', '');

			$i = 1;
			foreach($questions as $question) {
				$interviewContainer[$question->getId()]->addTextArea($iso, $i.'. '.$question->getQuestion()->getTranslationText($language));
				++$i;
			}
		}

		$rentalContainer->addMultiOptionList('spokenLanguages', $this->translate('13137'), $importantLanguagesForSelect);

		$rentalContainer->addText('bedroomCount', $this->translate('o100075'))
			->setRequired($this->translate('1257'))
			->addRule(self::NUMERIC, $this->translate('1257'));

		$rentalContainer->addText('roomsLayout', $this->translate('o100190'))
						->setOption('help', $this->translate('152269'));

		$amenities = $this->amenities->findByChildrenTypeForSelect();
		$rentalContainer->addMultiOptionList('children', 'o100169', $amenities);

		$amenities = $this->amenities->findByServiceTypeForSelect();
		$rentalContainer->addMultiOptionList('service', 'o100171', $amenities);

		$amenities = $this->amenities->findByWellnessTypeForSelect();
		$rentalContainer->addMultiOptionList('wellness', 'o100172', $amenities);

		$amenities = $this->amenities->findByKitchenTypeForSelect();
		$rentalContainer->addMultiOptionList('kitchen', 'o100174', $amenities);

		$amenities = $this->amenities->findByBathroomTypeForSelect();
		$rentalContainer->addMultiOptionList('bathroom', 'o100175', $amenities);

		$amenities = $this->amenities->findByNearByTypeForSelect();
		$rentalContainer->addMultiOptionList('nearBy', '152277', $amenities);

		$amenities = $this->amenities->findByRentalServicesTypeForSelect();
		$rentalContainer->addMultiOptionList('rentalServices', '152278', $amenities);

		$amenities = $this->amenities->findByOnFacilityTypeForSelect();
		$rentalContainer->addMultiOptionList('onFacility', '152279', $amenities);

		$amenities = $this->amenities->findBySportsFunTypeForSelect();
		$rentalContainer->addMultiOptionList('sportsFun', '152280', $amenities);

		$this->addSubmit('submit', 'o100083');

		$this->onValidate[] = callback($rentalContainer, 'validation');

	}

	public function setDefaultsValues()
	{
		return $this['rental']->setDefaultsValues();
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


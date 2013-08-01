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
		$phonePrefixes = $this->locationRepository->getCountriesPhonePrefixes($this->collator);
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

		$rentalContainer->addRentalPriceListContainer('priceList', $currency, $rental);
		$pricelistUpload = $rentalContainer->addRentalPriceUploadContainer('priceUpload', $rental);

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


		$rentalContainer->addText('price', 'o100078')
			->setOption('append', $currency->getIso() . ' ' . $this->translate('o100004'))
			->setOption('help', $this->translate('o100073'))
			->addRule(self::RANGE, $this->translate('o100105'), [0, 999999999999999])
			->setRequired('151883');

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

		/** @var $language \Entity\Language */
		foreach($supportedLanguages as $language) {
			$iso = $language->getIso();

			$nameContainer->addText($iso, $this->translate('152275', null, null, null, $language))
				->setOption('prepend', $this->translate($language->getName()) . ':')
				->setOption('help', $this->translate('o100071'));
				// ->addRule(self::LENGTH, $this->translate('o100101'), [2, 70]);

			$teaserContainer->addText($iso, $this->translate('152276', null, null, null, $language))
				->setOption('prepend', $this->translate($language->getName()) . ':')
				->setOption('help', '');
			$i = 1;
			foreach($questions as $question) {
				$interviewContainer[$question->getId()]->addTextArea($iso, $i.'. '.$question->getQuestion()->getTranslationText($language));
				++$i;
			}
		}

		$rentalContainer->addText('bedroomCount', $this->translate('o100075'))
			->setRequired($this->translate('1257'));

		$rentalContainer->addText('roomsLayout', $this->translate('o100190'))
						->setOption('help', $this->translate('152269'));

		$amenities = $this->amenityRepository->findByChildrenTypeForSelect($this->getTranslator(), $this->collator);
		$rentalContainer->addMultiOptionList('children', 'o100169', $amenities);

		$amenities = $this->amenityRepository->findByServiceTypeForSelect($this->getTranslator(), $this->collator);
		$rentalContainer->addMultiOptionList('service', 'o100171', $amenities);

		$amenities = $this->amenityRepository->findByWellnessTypeForSelect($this->getTranslator(), $this->collator);
		$rentalContainer->addMultiOptionList('wellness', 'o100172', $amenities);

		$amenities = $this->amenityRepository->findByKitchenTypeForSelect($this->getTranslator(), $this->collator);
		$rentalContainer->addMultiOptionList('kitchen', 'o100174', $amenities);

		$amenities = $this->amenityRepository->findByBathroomTypeForSelect($this->getTranslator(), $this->collator);
		$rentalContainer->addMultiOptionList('bathroom', 'o100175', $amenities);

		$amenities = $this->amenityRepository->findByNearByTypeForSelect($this->getTranslator(), $this->collator);
		$rentalContainer->addMultiOptionList('nearBy', '152277', $amenities);

		$amenities = $this->amenityRepository->findByRentalServicesTypeForSelect($this->getTranslator(), $this->collator);
		$rentalContainer->addMultiOptionList('rentalServices', '152278', $amenities);

		$amenities = $this->amenityRepository->findByOnFacilityTypeForSelect($this->getTranslator(), $this->collator);
		$rentalContainer->addMultiOptionList('onFacility', '152279', $amenities);

		$amenities = $this->amenityRepository->findBySportsFunTypeForSelect($this->getTranslator(), $this->collator);
		$rentalContainer->addMultiOptionList('sportsFun', '152280', $amenities);

		$this->addSubmit('submit', 'o100083');

		$this->onValidate[] = callback($this, 'validation');
		$this->onValidate[] = callback($rentalContainer, 'validation');
	}

	public function setDefaultsValues()
	{
		return $this['rental']->setDefaultsValues();
	}


	public function validation(Nette\Application\UI\Form $form)
	{

		$name = $this['rental']['name']->getValues();
		$nameIsFilled = FALSE;
		foreach($name as $key => $value) {
			if(strlen($value)) {
				$nameIsFilled = TRUE;
				break;
			}
		}

		if(!$nameIsFilled) {
			$this['rental']['name']['en']->addError($this->translate('o100071'));
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


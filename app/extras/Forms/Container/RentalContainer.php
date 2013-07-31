<?php

namespace Extras\Forms\Container;


use Doctrine\ORM\EntityManager;
use Entity\Contact\Address;
use Entity\Contact\Phone;
use Entity\Rental\Rental;
use Environment\Environment;
use FrontModule\Forms\RegistrationForm;
use Nette\Application\UI\Form;
use Nette\Localization\ITranslator;

class RentalContainer extends BaseContainer
{

	/**
	 * @var \Entity\Rental\Rental
	 */
	protected $rental;

	/**
	 * @var \Entity\Location\Location
	 */
	protected $country;

	/**
	 * @var \Environment\Collator
	 */
	protected $collator;

	/**
	 * @var \Nette\Localization\ITranslator
	 */
	protected $translator;

	/**
	 * @var \Repository\User\UserRepository
	 */
	protected $userRepository;

	/**
	 * @var \Repository\Rental\TypeRepository
	 */
	protected $rentalTypeRepository;

	/**
	 * @var \Repository\Rental\AmenityRepository
	 */
	protected $amenityRepository;

	protected $placementRepository;


	public function __construct(Environment $environment, Rental $rental = NULL,
								EntityManager $em, ITranslator $translator)
	{
		parent::__construct();
		$this->translator = $translator;
		$this->rental = $rental;
		$this->country = $environment->getPrimaryLocation();
		$this->collator = $environment->getLocale()->getCollator();

		$this->rentalTypeRepository = $em->getRepository(RENTAL_TYPE_ENTITY);
		$this->amenityRepository = $em->getRepository(RENTAL_AMENITY_ENTITY);
		$this->placementRepository = $em->getRepository(RENTAL_PLACEMENT_ENTITY);
		$this->userRepository = $em->getRepository(USER_ENTITY);

		$this->buildContainer();
	}


	public function buildContainer()
	{
		if($this->rental) {
			$this->addAddressContainer('address', $this->rental->address);
		} else {
			$this->addAddressContainer('address', $this->country);
		}

		$placement = $this->placementRepository->getForSelect($this->translator, $this->collator);
		$this->addSelect('placement', 'o100143', $placement)
			->setOption('help', $this->translate('152270'))
			->setPrompt('o854');

		$rentalTypes = $this->rentalTypeRepository->getForSelect($this->translator, $this->collator);
		$this->addRentalTypeContainer('type', $this->rental, $rentalTypes);

		$check = \Tools::$checkInOutOption;

		$this->addSelect('checkIn', 'o1586', $check)
						->setOption('help', $this->translate('151889'))
						->setPrompt('o854');

		$this->addSelect('checkOut', 'o1588', $check)
						->setOption('help', $this->translate('151890'))
						->setPrompt('o854');


		$this->addText('maxCapacity', 'o100072')
			//->addRule(self::RANGE, $this->translate('o100074'), [1, 1000])
			->setOption('help', $this->translate('o100073'))
			->setOption('append', $this->translate('o490', 2))
			->addRule(Form::INTEGER, $this->translate('o100106'))
			->addRule(Form::RANGE, $this->translate('o100106'), [0, 999999999999999]);


		$amenityPets = $this->amenityRepository->findByAnimalTypeForSelect($this->getTranslator(), $this->collator);
		$this->addSelect('pet', 'o100079', $amenityPets)
			->setPrompt('o854')
			->setRequired($this->translate('o100108'))
			//->setOption('help', $this->translate('o5956'))
		;

		$amenityBoard = $this->amenityRepository->findByBoardTypeForSelect($this->getTranslator(), $this->collator);
		$this->addMultiOptionList('board', 'o100080', $amenityBoard)
			->addRule(Form::FILLED, $this->translate('o100109'))//->setOption('help', $this->translate('o5956'))
		;

		$amenityAvailability = $this->amenityRepository->findByAvailabilityTypeForSelect($this->getTranslator(), $this->collator);
		$this->addSelect('ownerAvailability', 'o100082', $amenityAvailability)
			->setPrompt('o854')
			->setRequired($this->translate('o100107'));
			//->setOption('help', $this->translate('o5956'))
		;

		$this->addRentalPhotosContainer('photos', $this->rental);

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
			'url' => $this->rental->getUrlWithoutProtocol(),
			'phone' => $this->rental->getPhone(),
			'contactName' => $this->rental->getContactName(),
			'email' => $this->rental->getEmail(),
			'name' => $name,
			'teaser' => $teaser,
			'price' => $rental->getPrice()->getAmount($this->country->getDefaultCurrency()),
			'maxCapacity' => $rental->getMaxCapacity(),
			'interview' => $interview,
			'bedroomCount' => $rental->bedroomCount,
			'roomsLayout' => $rental->rooms,
			'checkIn' => $rental->getCheckIn(),
			'checkOut' => $rental->getCheckOut(),
			'ownerAvailability' => $rental->getOwnerAvailability()->getId(),
			'pet' => ($pet ? $pet->getId() : NULL),
			'placement' => $placement,

			'board' => array_map(function ($a) {
					return $a->getId();
				}, $rental->getBoardAmenities()
			),
			'children' => array_map(function ($a) {
					return $a->getId();
				}, $rental->getChildrenAmenities()
			),
			'service' => array_map(function ($a) {
					return $a->getId();
				}, $rental->getServiceAmenities()
			),
			'wellness' => array_map(function ($a) {
					return $a->getId();
				}, $rental->getWellnessAmenities()
			),
			'kitchen' => array_map(function ($a) {
					return $a->getId();
				}, $rental->getKitchenAmenities()
			),
			'bathroom' => array_map(function ($a) {
					return $a->getId();
				}, $rental->getBathroomAmenities()
			),
			'nearBy' => array_map(function ($a) {
					return $a->getId();
				}, $rental->getNearByAmenities()
			),
			'rentalServices' => array_map(function ($a) {
					return $a->getId();
				}, $rental->getRentalServicesAmenities()
			),
			'onFacility' => array_map(function ($a) {
					return $a->getId();
				}, $rental->getOnFacilityAmenities()
			),
			'sportsFun' => array_map(function ($a) {
					return $a->getId();
				}, $rental->getSportsFunAmenities()
			),
		];

		$this->setDefaults($defaults);
	}


	public function getMainControl()
	{
		return $this['type'];
	}

	public function validation() {
		$this['address']->validate();
		$this['phone']->validate();

		$photos = $this['photos']->getFormattedValues();
		if(count($photos->images) < 3) {
			$this['photos']['upload']->addError($this->translate('1294'));
		}
	}




	/**
	 * @return ITranslator
	 */
	protected function getTranslator()
	{
		return $this->translator;
	}


	/**
	 * @return mixed
	 */
	protected function translate()
	{
		$args = func_get_args();

		return call_user_func_array(array($this->getTranslator(), 'translate'), $args);
	}


}


interface IRentalContainerFactory
{

	/**
	 * @param Environment $environment
	 * @param \Entity\Rental\Rental $rental
	 *
	 * @return RentalContainer
	 */
	public function create(Environment $environment, Rental $rental = NULL);
}

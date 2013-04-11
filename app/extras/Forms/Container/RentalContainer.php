<?php

namespace Extras\Forms\Container;


use Doctrine\ORM\EntityManager;
use Entity\Contact\Address;
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

		$rentalTypes = $this->rentalTypeRepository->getForSelect($this->translator, $this->collator);


		$this->addAddressContainer('address', $this->country);
		$placement = $this->placementRepository->getForSelect($this->translator, $this->collator);

		$this->addSelect('placement', 'Placement', $placement)
			->setOption('help', '');


		$this->addText('name', 'o886')
			->setOption('help', $this->translate('o100071'))
			->addRule(Form::LENGTH, $this->translate('o100101'), [2, 70]);


		$this->addRentalTypeContainer('type', $rentalTypes);

		$check = ['dohoda', 'hocikedy', '8:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00'];
		$this->addSelect('checkIn', 'o1586', $check)//->setOption('help', $this->translate('o5956'))
		;
		$this->addSelect('checkOut', 'o1588', $check)
			->setOption('help', $this->translate('o5956'));

		$this->addText('maxCapacity', 'o100072')
			//->addRule(self::RANGE, $this->translate('o100074'), [1, 1000])
			->setOption('help', $this->translate('o100073'))
			->setOption('prepend', $this->translate('o490', 2) . ':')
			->addRule(Form::INTEGER, $this->translate('o100106'))
			->addRule(Form::RANGE, $this->translate('o100106'), [0, 999999999999999]);

//		$rentalContainer->addText('bedroomCount', 'o100075')
//			//->addRule(self::RANGE, $this->translate('o100074'), [1, 1000])
//			//->setOption('help', $this->translate('o5956'))
//			;

		$this->addCheckbox('separateGroups', 'o100076')
			->setOption('help', $this->translate('o100077'));

		$this->addText('price', 'o100078')
			->setOption('append', $this->country->defaultCurrency->iso . ' ' . $this->translate('o100004'))
			->setOption('help', $this->translate('o100073'))
			->addRule(Form::INTEGER, $this->translate('o100105'))
			->addRule(Form::RANGE, $this->translate('o100105'), [0, 999999999999999]);

		$amenityPets = $this->amenityRepository->findByAnimalTypeForSelect($this->getTranslator(), $this->collator);
		$this->addSelect('pet', 'o100079', $amenityPets)
			->setPrompt('o854')
			->setRequired($this->translate('o100108'))//->setOption('help', $this->translate('o5956'))
		;

		$amenityBoard = $this->amenityRepository->findByBoardTypeForSelect($this->getTranslator(), $this->collator);
		$this->addMultiOptionList('board', 'o100080', $amenityBoard)
			->addRule(Form::FILLED, $this->translate('o100109'))//->setOption('help', $this->translate('o5956'))
		;

		$amenityImportant = $this->amenityRepository->findImportantForSelect($this->getTranslator(), $this->collator);
		$this->addMultiOptionList('important', 'o100081', $amenityImportant)//->setOption('help', $this->translate('o5956'))
		;

		$amenityAvailability = $this->amenityRepository->findByAvailabilityTypeForSelect($this->getTranslator(), $this->collator);
		$this->addSelect('ownerAvailability', 'o100082', $amenityAvailability)
			->setPrompt('o854')
			->setRequired($this->translate('o100107'));
		//->setOption('help', $this->translate('o5956'))
		;

		$this->addRentalPhotosContainer('photos', $this->rental);

	}


	public function getMainControl()
	{
		return $this['type'];
	}


	/**
	 * @param RegistrationForm $form
	 */
	public function validation(RegistrationForm $form)
	{
		$values = $form->getValues();

		$phone = $values->phone;
		if (!$phone->phone instanceof \Entity\Contact\Phone) {
			$form['phone']->getMainControl()->addError($this->translate('o1039'));
		}

		$rentalValues = $values->rental;
		$photosSort = $rentalValues->photos->sort;
		if (count($photosSort) < 3) {
			$form['rental']['photos']->getMainControl()->addError($this->translate('o100111'));
		}

		$address = $rentalValues->address;
		if(!$address->addressEntity instanceof Address) {
			$form['rental']['address']->getMainControl()->addError($this->translate('o100134'));
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

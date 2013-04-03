<?php

namespace FrontModule\Forms\Rental;

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
	 * @param \Entity\Rental\Rental $rental
	 * @param \Environment\Environment $environment
	 * @param IRentalContainerFactory $rentalContainerFactory
	 * @param EntityManager $em
	 * @param ITranslator $translator
	 */
	public function __construct(Rental $rental,Environment $environment, IRentalContainerFactory $rentalContainerFactory,
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
		parent::__construct($translator);
	}


	public function buildForm()
	{
		$phonePrefixes = $this->locationRepository->getCountriesPhonePrefixes();

		$this->addText('email', 'o1096')
			->setOption('help', $this->translate('o3095'))
			->setOption('prepend', '<i class="icon-envelope"></i>')
			->setAttribute('placeholder', 'email@email.com')
			->addRule(self::EMAIL, $this->translate('o407'));
		;
		$this->addPassword('password', 'o997')
			->setOption('help', $this->translate('o3096'))
			->setOption('prepend', '<i class="icon-lock"></i>')
			->addRule(self::MIN_LENGTH, $this->translate('o856'), 6);
		;

//		$this->addText('name', 'o100070')
//			->setOption('help', $this->translate('o100071'))
//	        //->addRule(Form::MAX_LENGTH, 'o100101', 70);
//			;
//
		$this->addPhoneContainer('phone', 'o10899', $phonePrefixes);


		$this->addText('url', 'o977')
			->setOption('help', $this->translate('o978'))
			->setOption('prepend', 'http://')
			->addRule(self::URL, $this->translate('o100102'));
		;

		$rentalContainer = $this->rentalContainerFactory->create($this->environment);
		$this['rental'] = $rentalContainer;

		$this->addSubmit('submit', $this->translate('o100083'));

		$this->onValidate[] = callback($this, 'validation');
		$this->onValidate[] = $rentalContainer->validation;
	}

	public function setDefaultsValues()
	{
		$rental = $this->rental;
		$defaults = [

			//'referrer' => 'luzbo',
			'email' => $rental->getEmail(),
			'url' => $rental->getUrl(),

			'phone' => $rental->getPhone(),
			'rental' => [
				'name' => $this->translate($rental->getName()),
				'price' => $rental->getPrice()->getSourceAmount(),
				'maxCapacity' => $rental->getMaxCapacity(),
				'type' => [
					'type' => $rental->getType()->getId(),
					'classification' => $rental->getClassification(),
				],
				'board' => $rental->getBoardAmenities(),
				'ownerAvailability' => $rental->getOwnerAvailability(),
				'pet' => $rental->getPetAmenity(),

				'address' => $rental->getAddress(),
			],
		];
		d($defaults);
		$this->setDefaults($defaults);
	}

	public function validation(RegistrationForm $form){
		$values = $form->getValues();

		$email = $values->email;
		if($email && !$form['email']->hasErrors()) {
			$emailIsOccupied = $this->userRepository->findOneByLogin($email);
			if($emailIsOccupied) {
				$form['email']->addError($this->translate('o852'));
			}
		}
	}


}


interface IRentalEditFormFactory {

	/**
	 * @param \Entity\Rental\Rental $rental
	 * @param \Environment\Environment $environment
	 *
	 * @return RentalEditForm
	 */
	public function create(Rental $rental, Environment $environment);
}


<?php

namespace FrontModule\Forms;

use Doctrine\ORM\EntityManager;
use Environment\Collator;
use Environment\Environment;
use Extras\Forms\Container\IRentalContainerFactory;
use Nette;
use Nette\Localization\ITranslator;
use Entity\Location\Location;
use OwnerModule\BasePresenter;
use Repository\Location\LocationRepository;
use Repository\LanguageRepository;
use Extras\Forms\Container\AddressContainer;

/**
 * RegistrationForm class
 *
 * @author Dávid Ďurika
 */
class RegistrationForm extends \FrontModule\Forms\BaseForm
{

	/**
	 * @var \Environment\Environment
	 */
	protected $environment;

	/**
	 * @var \Entity\Location\Location
	 */
	protected $country;

	/**
	 * @var \Nette\Application\UI\Presenter
	 */
	protected $uiPresenter;

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
	 * @var \Repository\CurrencyRepository
	 */
	protected $currencyRepository;

	/**
	 * @var \Repository\Rental\AmenityRepository
	 */
	protected $amenityRepository;

	/**
	 * @param Environment $environment
	 * @param Nette\Application\UI\Presenter $presenter
	 * @param \Extras\Forms\Container\IRentalContainerFactory $rentalContainerFactory
	 * @param EntityManager $em
	 * @param ITranslator $translator
	 */
	public function __construct(Environment $environment, Nette\Application\UI\Presenter $presenter,
								IRentalContainerFactory $rentalContainerFactory,
								EntityManager $em, ITranslator $translator)
	{
		$this->environment = $environment;
		$this->country = $environment->getPrimaryLocation();
		$this->uiPresenter = $presenter;
		$this->collator = $environment->getLocale()->getCollator();
		$this->rentalContainerFactory = $rentalContainerFactory;

		$this->locationRepository = $em->getRepository(LOCATION_ENTITY);
		$this->languageRepository = $em->getRepository(LANGUAGE_ENTITY);
		$this->userRepository = $em->getRepository(USER_ENTITY);
		$this->currencyRepository = $em->getRepository(CURRENCY_ENTITY);
		$this->amenityRepository = $em->getRepository(RENTAL_AMENITY_ENTITY);
		parent::__construct($translator);
	}


	public function buildForm()
	{
		$countries = $this->locationRepository->getCountriesForSelect($this->translator, $this->collator, $this->uiPresenter);
		$languages = $this->languageRepository->getForSelectWithLinks($this->translator, $this->collator, $this->uiPresenter);
		$phonePrefixes = $this->locationRepository->getCountriesPhonePrefixes();

		$this->addSelect('country', 'o1094', $countries)->setOption('help', $this->translate('o5956'));
		$this->addSelect('language', 'o4639', $languages)->setOption('help', $this->translate('o5957'));


		$this->addText('email', 'o1096')
			->setOption('help', $this->translate('o3095'))
			->setOption('prepend', '<i class="icon-envelope"></i>')
			->setAttribute('placeholder', 'email@email.com')
			->addRule(self::EMAIL, $this->translate('o100144'));
		;
		$this->addPassword('password', 'o997')
			->setOption('help', $this->translate('o100145'))
			->setOption('prepend', '<i class="icon-lock"></i>')
			->addRule(self::MIN_LENGTH, $this->translate('o100145'), 5);
		;

		$this->addText('url', 'o977')
			->setOption('help', $this->translate('o978'))
			->setOption('prepend', 'http://')
			->addRule(self::URL, $this->translate('o100102'));
		;


		$rentalContainer = $this->rentalContainerFactory->create($this->environment);
		$this['rental'] = $rentalContainer;

		$phoneContainer = $rentalContainer->addPhoneContainer('phone', 'o10899', $phonePrefixes);

		$phoneContainer->getPrefixControl()
			->setDefaultValue($this->environment->getPrimaryLocation()->getPhonePrefix());

		$rentalContainer->addText('name', 'o886')
			->setOption('help', $this->translate('o100071'))
			->addRule(self::LENGTH, $this->translate('o100101'), [2, 70]);

		$currency = $this->country->getDefaultCurrency();
		if($currency) {
			$rentalContainer->addText('price', 'o100078')
				->setOption('append', $currency->getIso() . ' ' . $this->translate('o100004'))
				->setOption('help', $this->translate('o100073'))
				->addRule(self::RANGE, $this->translate('o100105'), [0, 999999999999999]);
		} else {
			$currencies = $this->currencyRepository->getForSelect($this->translator, $this->collator);
			$rentalContainer->addPriceContainer('price', 'o100078', $currencies);
		}

		$amenityImportant = $this->amenityRepository->findImportantForSelect($this->getTranslator(), $this->collator);
		$rentalContainer->addMultiOptionList('important', 'o100081', $amenityImportant)//->setOption('help', $this->translate('o5956'))
		;


		$this->addSubmit('submit', 'o1099');

		$this->onValidate[] = callback($this, 'validation');
		$this->onValidate[] = $rentalContainer->validation;
	}


	public function setDefaultsValues()
	{
		$defaults = [
			'country' => $this->environment->getPrimaryLocation()->getId(),
			'language' => $this->environment->getLanguage()->getId(),


			'email' => Nette\Utils\Strings::random(5) . '@email.com',
			'url' => 'google.com',
			'password' => 'adsfasdf',
			'name' => 'Harlem Shake',
			'rental' => [
				'phone' => [
					'prefix' => '421',
					'number' => '908 123 789'
				],
				'name' => 'Chata Test',
				'maxCapacity' => 15,
				'type' => [
					'type' => 3,
					'classification' => 2,
				],
				'board' => [287],
				'ownerAvailability' => 275,
				'pet' => 296,
				'placement' => [1],
				'important' => [50, 188],

				'address' => [
					'address' => 'Ľ. Štúra 8, Nové Zámky, Slovakia',
				],
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
				$form['email']->addError($this->translate('o2610'));
				$form->presenter->flashMessage('o2610', BasePresenter::FLASH_ERROR);
			}
		}
	}


}


interface IRegistrationFormFactory
{

	/**
	 * @param \Environment\Environment $environment
	 * @param Nette\Application\UI\Presenter $presenter
	 *
	 * @return RegistrationForm
	 */
	public function create(Environment $environment, Nette\Application\UI\Presenter $presenter);
}

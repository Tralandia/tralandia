<?php

namespace FrontModule\Forms;

use Nette;
use Nette\Localization\ITranslator;
use Entity\Location\Location;
use Repository\Location\LocationRepository;
use Repository\LanguageRepository;
use Repository\CurrencyRepository;
use Repository\BaseRepository;

/**
 * RegistrationForm class
 *
 * @author Dávid Ďurika
 */
class RegistrationForm extends \FrontModule\Forms\BaseForm {

	/**
	 * @var \Entity\Location\Location
	 */
	private $country;

	/**
	 * @var \Repository\Location\LocationRepository
	 */
	private $locationRepository;

	/**
	 * @var \Repository\LanguageRepository
	 */
	private $languageRepository;

	/**
	 * @var \Repository\RentalType
	 */
	private $rentalTypeRepository;

	/**
	 * @var \Repository\CurrencyRepository
	 */
	private $currencyRepository;

	public function __construct(Location $country, LocationRepository $locationRepository, LanguageRepository $languageRepository, BaseRepository $rentalTypeRepository, CurrencyRepository $currencyRepository, ITranslator $translator)
	{
		$this->country = $country;
		$this->locationRepository = $locationRepository;
		$this->languageRepository = $languageRepository;
		$this->rentalTypeRepository = $rentalTypeRepository;
		$this->currencyRepository = $currencyRepository;
		parent::__construct($translator);
	}


	public function buildForm()
	{
		$countries = $this->locationRepository->getCountriesForSelect();
		$languages = $this->languageRepository->getForSelect();
		$phonePrefixes = $this->locationRepository->getCountriesPhonePrefixes();
		$rentalTypes = $this->rentalTypeRepository->getForSelect();
		$currencies = $this->currencyRepository->getForSelect();

		$this->addSelect('country', 'Country', $countries);
		$this->addSelect('language', 'Language', $languages);
		$this->addText('referrer', 'Referrer');
		$this->addText('email', 'Email');

		$phone = $this->addContainer('phone');
		$phone->addSelect('prefix', '', $phonePrefixes);
		$phone->addText('number', 'Phone');

		$this->addText('www', 'WWW');

		$this->addPassword('password1', 'Password');
		$this->addPassword('password2', 'Confirm Password');

		$this->addText('address', 'Address');
		$this->addText('gps', 'GPS');

		$rental = $this->addContainer('rental');
		$rental->addText('name', 'Rental Name');
		$rental->addSelect('type', 'Rental Type', $rentalTypes);
		$rental->addSelect('classification', 'Classification', array('*', '**', '***', '****', '*****'));

		$rental->addText('price', 'Price category');
		$rental->addSelect('currency', '', $currencies);

		$rental->addText('maxCapacity', 'Max Capacity');

		$this->addSelect('legalForm', 'Legal Form');
		$this->addText('clientName', 'Client name');
		$this->addSelect('clientCountry', 'Client country', $countries);

		$this->addSubmit('register', 'Register');

	}

	public function setDefaultsValues()
	{
		$this->setDefaults([
			'country' => $this->country->getId(),
			'clientCountry' => $this->country->getId(),
			'rental[currency]' => $this->country->getDefaultCurrency(),
		]);
	}

}
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
class RegistrationForm extends \FrontModule\Forms\BaseForm
{

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
	 * @var \Repository\Rental\TypeRepository
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

		$this['phone'] = new \Extras\Forms\Container\PhoneContainer('Phone', $phonePrefixes);

		$this->addText('url', 'WWW');

		$this->addPassword('password', 'Password');
		$this->addPassword('password2', 'Confirm Password');

		$this['address'] = new \Extras\Forms\Container\AddressContainer($countries);

		$this->addText('rentalName', 'Rental Name');
		$this->addSelect('rentalType', 'Rental Type', $rentalTypes);
		$this->addSelect('rentalClassification', 'Classification', array('*', '**', '***', '****', '*****'));

		$this->addText('rentalPrice', 'Price category');

		$this->addText('rentalMaxCapacity', 'Max Capacity');

		$this->addSubmit('submit', 'OK let\'s do this shit');

	}

	public function setDefaultsValues()
	{
		$defaults = [
			'country' => $this->country->getId(),
			'language' => $this->country->getDefaultLanguage()->getId(),
			'phone' => ['prefix' => $this->country->getIso()],

			'referrer' => 'luzbo',
			'email' => 'email@' . \Tra\Utils\Strings::random(6) . '.com',
			'www' => 'google.com',
			'password' => 'adsfasdf',
			'password2' => 'adsfasdf',
			'rentalAddress' => 'Nesvady',
			'rentalGps' => '1423345',
			'rentalName' => 'Chata Test',
			'rentalPrice' => '3',
			'rentalMaxCapacity' => 15,
		];
		$this->setDefaults($defaults);
	}

}

interface IRegistrationFormFactory
{
	/**
	 * @param \Entity\Location\Location $country
	 *
	 * @return RegistrationForm
	 */
	public function create(\Entity\Location\Location $country);
}

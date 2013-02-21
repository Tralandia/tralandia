<?php

namespace FrontModule\Forms;

use Doctrine\ORM\EntityManager;
use Nette;
use Nette\Localization\ITranslator;
use Entity\Location\Location;
use Repository\Location\LocationRepository;
use Repository\LanguageRepository;
use Repository\CurrencyRepository;
use Repository\BaseRepository;
use Extras\Forms\Container\AddressContainer;
use Extras\Forms\Container\PhoneContainer;

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
	 * @param \Entity\Location\Location $country
	 * @param \Doctrine\ORM\EntityManager $em
	 * @param \Nette\Localization\ITranslator $translator
	 */
	public function __construct(Location $country, EntityManager $em, ITranslator $translator)
	{
		$this->country = $country;
		d($country);
		$this->locationRepository = $em->getRepository('\Entity\Location\Location');
		$this->languageRepository = $em->getRepository('\Entity\Language');
		$this->rentalTypeRepository = $em->getRepository('\Entity\Rental\Type');
		parent::__construct($translator);
	}


	public function buildForm()
	{
		$countries = $this->locationRepository->getCountriesForSelect();
		$phonePrefixes = $this->locationRepository->getCountriesPhonePrefixes();
		$languages = $this->languageRepository->getForSelect();
		$rentalTypes = $this->rentalTypeRepository->getForSelect();

		$this->addSelect('country', 'Country', $countries);
		$this->addSelect('language', 'Language', $languages);
		$this->addText('referrer', 'Referrer');
		$this->addText('email', 'Email');

		$this['phone'] = new PhoneContainer('Phone', $phonePrefixes);

		$this->addText('url', 'WWW');

		$this->addPassword('password', 'Password');
		$this->addPassword('password2', 'Confirm Password');


		$this->addText('rentalName', 'Rental Name');
		$this->addSelect('rentalType', 'Rental Type', $rentalTypes);
		$this->addSelect('rentalClassification', 'Classification', array('*', '**', '***', '****', '*****'));
		$this['rentalAddress'] = new AddressContainer($countries, $this->country);

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
			'rentalGps' => '1423345',
			'rentalName' => 'Chata Test',
			'rentalPrice' => '3',
			'rentalMaxCapacity' => 15,
			'rentalAddress' => [
				'address' => 'Puskinova 9',
				'locality' => 'Nesvady',
				'postalCode' => '94651',
				'location' => $this->country->getId(),
				'latitude' => $this->country->getGps()->getLatitude(),
				'longitude' => $this->country->getGps()->getLongitude(),
			],
		];
		$this->setDefaults($defaults);
		$this['rentalAddress']->setDefaultValues();
	}

}


interface IRegistrationFormFactory {
	/**
	 * @param \Entity\Location\Location $country
	 *
	 * @return RegistrationForm
	 */
	public function create(\Entity\Location\Location $country);
}


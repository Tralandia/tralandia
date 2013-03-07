<?php

namespace FrontModule\Forms;

use Doctrine\ORM\EntityManager;
use Image\RentalImageManager;
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
	protected $country;

	/**
	 * @var \Repository\Location\LocationRepository
	 */
	protected $locationRepository;

	/**
	 * @var \Repository\LanguageRepository
	 */
	protected $languageRepository;

	/**
	 * @var \Repository\Rental\TypeRepository
	 */
	protected $rentalTypeRepository;

	/**
	 * @var \Image\RentalImageManager
	 */
	protected $imageManager;

	/**
	 * @var \Repository\Rental\AmenityRepository
	 */
	protected $amenityRepository;

	/**
	 * @param \Entity\Location\Location $country
	 * @param \Doctrine\ORM\EntityManager $em
	 * @param \Image\RentalImageManager $imageManager
	 * @param \Nette\Localization\ITranslator $translator
	 */
	public function __construct(Location $country, EntityManager $em, RentalImageManager $imageManager,
								ITranslator $translator)
	{
		$this->country = $country;
		$this->imageManager = $imageManager;
		$this->locationRepository = $em->getRepository('\Entity\Location\Location');
		$this->languageRepository = $em->getRepository('\Entity\Language');
		$this->rentalTypeRepository = $em->getRepository('\Entity\Rental\Type');
		$this->amenityRepository = $em->getRepository('\Entity\Rental\Amenity');
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


		$this->addText('email', 'Email');
		$this->addPassword('password', 'Password');
		$this->addPassword('password2', 'Confirm Password');

		$this->addText('name', 'Name');
		$this['phone'] = new PhoneContainer('Phone', $phonePrefixes);
		$this->addText('url', 'WWW');

		$rentalContainer = $this->addContainer('rental');

		$rentalContainer['address'] = new AddressContainer($countries, $this->country);
		$amenityLocation = $this->amenityRepository->findByLocationTypeForSelect();

		$rentalContainer->addMultiOptionList('amenityLocation', 'Amenity Location', $amenityLocation);


		$rentalContainer->addText('name', 'Rental Name');
		$rentalContainer->addSelect('type', 'Rental Type', $rentalTypes);
		$rentalContainer->addSelect('classification', 'Classification', array('*', '**', '***', '****', '*****'));

		$check = ['dohoda', 'hocikedy', '8:00', '10:00', '15:00', '21:00'];
		$rentalContainer->addSelect('checkIn', 'Check In', $check);
		$rentalContainer->addSelect('checkOut', 'Check Out', $check);

		$rentalContainer->addText('maxCapacity', 'Max Capacity')
			->addRule(self::RANGE, 'zadaj coslo od %d do %d', [1, 1000]);

		$rentalContainer->addText('bedroomCount', 'Bedroom Count')
			->addRule(self::RANGE, 'zadaj coslo od %d do %d', [1, 1000]);

		$rentalContainer->addCheckbox('separateGroups', 'Separate groups');

		$rentalContainer->addText('price', 'Price category');

		$pet = ['Luzbo', 'je', 'super!'];
		$rentalContainer->addSelect('pet', 'Pet', $pet);

		$amenityBoard = $this->amenityRepository->findByBoardTypeForSelect();
		$rentalContainer->addMultiOptionList('board', 'Amenity board', $amenityBoard);

		$amenityImportant = $this->amenityRepository->findImportantForSelect();
		$rentalContainer->addMultiOptionList('important', 'important amenities', $amenityImportant);

		$amenityAvailability = $this->amenityRepository->findByAvailabilityTypeForSelect();
		$rentalContainer->addMultiOptionList('ownerAvailability', 'availability', $amenityAvailability);


		$rentalContainer['photos'] = new \Extras\Forms\Container\RentalPhotosContainer(NULL, $this->imageManager);

		$this->addSubmit('submit', 'OK let\'s do this shit');

	}

	public function setDefaultsValues()
	{
		$defaults = [
			'country' => $this->country->getId(),
			'language' => $this->country->getDefaultLanguage()->getId(),
			'phone' => ['prefix' => $this->country->getIso()],

			'referrer' => 'luzbo',
			'email' => 'email@' . \Nette\Utils\Strings::random(6) . '.com',
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
		$this['rental']['address']->setDefaultValues();
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


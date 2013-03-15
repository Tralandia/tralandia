<?php

namespace FrontModule\Forms;

use Doctrine\ORM\EntityManager;
use Environment\Collator;
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
	 * @var \Environment\Collator
	 */
	protected $collator;

	/**
	 * @var \Nette\Localization\ITranslator
	 */
	protected $translator;

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
	 * @param \Environment\Collator $collator
	 * @param \Doctrine\ORM\EntityManager $em
	 * @param \Image\RentalImageManager $imageManager
	 * @param \Nette\Localization\ITranslator $translator
	 */
	public function __construct(Location $country, Collator $collator, EntityManager $em, RentalImageManager $imageManager,
								ITranslator $translator)
	{
		$this->country = $country;
		$this->collator = $collator;
		$this->translator = $translator;
		$this->imageManager = $imageManager;
		$this->locationRepository = $em->getRepository('\Entity\Location\Location');
		$this->languageRepository = $em->getRepository('\Entity\Language');
		$this->rentalTypeRepository = $em->getRepository('\Entity\Rental\Type');
		$this->amenityRepository = $em->getRepository('\Entity\Rental\Amenity');
		parent::__construct($translator);
	}


	public function buildForm()
	{
		$countries = $this->locationRepository->getCountriesForSelect($this->translator, $this->collator);
		$phonePrefixes = $this->locationRepository->getCountriesPhonePrefixes();
		$languages = $this->languageRepository->getForSelect($this->translator, $this->collator);
		$rentalTypes = $this->rentalTypeRepository->getForSelect($this->translator, $this->collator);

		$this->addSelect('country', 'o1094', $countries)->setOption('help', $this->translate('o5956'));
		$this->addSelect('language', 'o4639', $languages)->setOption('help', $this->translate('o5957'));


		$this->addText('email', 'o1096')
			->setOption('help', $this->translate('o3095'))
			->setAttribute('placeholder', 'email@email.com')
			;
		$this->addPassword('password', 'o997')
			->setOption('help', $this->translate('o3096'))
			;

		$this->addText('name', 'o100070')
			->setOption('help', $this->translate('o100071'))
			;

		$this['phone'] = new PhoneContainer('o10899', $phonePrefixes);
		
		$this->addText('url', 'o977')
			->setOption('help', $this->translate('o978'))
			;

		$rentalContainer = $this->addContainer('rental');

		$rentalContainer['address'] = new AddressContainer($countries, $this->country);
		$amenityLocation = $this->amenityRepository->findByLocationTypeForSelect($this->translator, $this->collator);

		$rentalContainer->addMultiOptionList('amenityLocation', 'Amenity Location')
			->setOption('help', $this->translate('o5956'))
		;


		$rentalContainer->addText('name', 'o886')
			->setOption('help', $this->translate('o100071'))
			;
		$rentalContainer->addSelect('type', 'o883', $rentalTypes)
			//->setOption('help', $this->translate('o5956'))
			;
		$rentalContainer->addSelect('classification', 'o25137', array('★', '★ ★', '★ ★ ★', '★ ★ ★ ★', '★ ★ ★ ★ ★'))
			//->setOption('help', $this->translate('o5956'))
			;

		$check = ['dohoda', 'hocikedy', '8:00', '10:00', '15:00', '21:00'];
		$rentalContainer->addSelect('checkIn', 'o1586', $check)
			//->setOption('help', $this->translate('o5956'))
			;
		$rentalContainer->addSelect('checkOut', 'o1588', $check)
			->setOption('help', $this->translate('o5956'))
			;

		$rentalContainer->addText('maxCapacity', 'o100072')
			->addRule(self::RANGE, $this->translate('o100074'), [1, 1000])
			->setOption('help', $this->translate('o100073'))
			;

		$rentalContainer->addText('bedroomCount', 'o100075')
			->addRule(self::RANGE, $this->translate('o100074'), [1, 1000])
			//->setOption('help', $this->translate('o5956'))
			;

		$rentalContainer->addCheckbox('separateGroups', 'o100076')
			->setOption('help', $this->translate('o100077'))
			;

		$rentalContainer->addText('price', 'o100078')
			->setOption('append', $this->country->defaultCurrency->iso.' '.$this->translate('o100004'))
			//->setOption('help', $this->translate('o5956'))
			;

		$pet = ['Luzbo', 'je', 'super!'];
		$rentalContainer->addMultiOptionList('pet', 'o100079', $pet)
			//->setOption('help', $this->translate('o5956'))
			;

		$amenityBoard = $this->amenityRepository->findByBoardTypeForSelect($this->translator, $this->collator);
		$rentalContainer->addMultiOptionList('board', 'o100080', $amenityBoard)
			//->setOption('help', $this->translate('o5956'))
			;

		$amenityImportant = $this->amenityRepository->findImportantForSelect($this->translator, $this->collator);
		$rentalContainer->addMultiOptionList('important', 'o100081', $amenityImportant)
			//->setOption('help', $this->translate('o5956'))
			;

		$amenityAvailability = $this->amenityRepository->findByAvailabilityTypeForSelect($this->translator, $this->collator);
		$rentalContainer->addMultiOptionList('ownerAvailability', 'o100082', $amenityAvailability)
			//->setOption('help', $this->translate('o5956'))
			;

		$rentalContainer['photos'] = new \Extras\Forms\Container\RentalPhotosContainer(NULL, $this->imageManager);

		$this->addSubmit('submit', $this->translate('o100083'));

	}

	public function setDefaultsValues()
	{
		$defaults = [
			'country' => $this->country->getId(),
			'language' => $this->country->getDefaultLanguage()->getId(),

			//'referrer' => 'luzbo',
			'email' => Nette\Utils\Strings::random(5).'@email.com',
			'url' => 'google.com',
			'password' => 'adsfasdf',
			'name' => 'Harlem Shake',
			'phone' => [
				'prefix' => '421',
				'number' => '908 123 789'
			],
			'rental' => [
				'name' => 'Chata Test',
				'price' => '3',
				'maxCapacity' => 15,
				'type' => 3,
				'pet' => [1],

				//'address' => 'Ľ. Štúra 8, Nové Zámky, Slovakia',
			],
		];
		$this->setDefaults($defaults);
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


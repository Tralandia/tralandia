<?php

namespace FrontModule\Forms;

use Doctrine\ORM\EntityManager;
use Environment\Collator;
use Extras\Forms\Container\RentalTypeContainer;
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
	 * @var \Nette\Application\UI\Presenter
	 */
	protected $uiPresenter;

	/**
	 * @var \Environment\Collator
	 */
	protected $collator;

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
	 * @var \Repository\Rental\TypeRepository
	 */
	protected $rentalTypeRepository;

	/**
	 * @var \Repository\Rental\AmenityRepository
	 */
	protected $amenityRepository;

	/**
	 * @param \Entity\Location\Location $country
	 * @param \Nette\Application\UI\Presenter $presenter
	 * @param \Environment\Collator $collator
	 * @param \Doctrine\ORM\EntityManager $em
	 * @param \Nette\Localization\ITranslator $translator
	 */
	public function __construct(Location $country, Nette\Application\UI\Presenter $presenter,
								Collator $collator, EntityManager $em, ITranslator $translator)
	{
		$this->country = $country;
		$this->uiPresenter = $presenter;
		$this->collator = $collator;
		$this->locationRepository = $em->getRepository(LOCATION_ENTITY);
		$this->languageRepository = $em->getRepository(LANGUAGE_ENTITY);
		$this->rentalTypeRepository = $em->getRepository(RENTAL_TYPE_ENTITY);
		$this->amenityRepository = $em->getRepository(RENTAL_AMENITY_ENTITY);
		$this->userRepository = $em->getRepository(USER_ENTITY);
		parent::__construct($translator);
	}


	public function buildForm()
	{
		$countries = $this->locationRepository->getCountriesForSelect($this->translator, $this->collator, $this->uiPresenter);
		$languages = $this->languageRepository->getForSelectWithLinks($this->translator, $this->collator, $this->uiPresenter);
		$phonePrefixes = $this->locationRepository->getCountriesPhonePrefixes();
		$rentalTypes = $this->rentalTypeRepository->getForSelect($this->translator, $this->collator);

		$this->addSelect('country', 'o1094', $countries)->setOption('help', $this->translate('o5956'));
		$this->addSelect('language', 'o4639', $languages)->setOption('help', $this->translate('o5957'));


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

		$rentalContainer = $this->addContainer('rental');

		$rentalContainer['address'] = new AddressContainer($this->country);
		$amenityLocation = $this->amenityRepository->findByLocationTypeForSelect($this->translator, $this->collator);

		$rentalContainer->addMultiOptionList('amenityLocation', 'Amenity Location')
			->setOption('help', '')
		;


		$rentalContainer->addText('name', 'o886')
			->setOption('help', $this->translate('o100071'))
			->addRule(self::LENGTH, $this->translate('o100101'), [2, 70])
			;


		$rentalContainer->addRentalTypeContainer('type', $rentalTypes);

		$check = ['dohoda', 'hocikedy', '8:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00'];
		$rentalContainer->addSelect('checkIn', 'o1586', $check)
			//->setOption('help', $this->translate('o5956'))
			;
		$rentalContainer->addSelect('checkOut', 'o1588', $check)
			->setOption('help', $this->translate('o5956'))
			;

		$rentalContainer->addText('maxCapacity', 'o100072')
			//->addRule(self::RANGE, $this->translate('o100074'), [1, 1000])
			->setOption('help', $this->translate('o100073'))
			->setOption('prepend', $this->translate('o490', 2).':')
			->addRule(self::INTEGER, $this->translate('o100106'))
			->addRule(self::RANGE, $this->translate('o100106'), [0, 999999999999999])
			;

//		$rentalContainer->addText('bedroomCount', 'o100075')
//			//->addRule(self::RANGE, $this->translate('o100074'), [1, 1000])
//			//->setOption('help', $this->translate('o5956'))
//			;

		$rentalContainer->addCheckbox('separateGroups', 'o100076')
			->setOption('help', $this->translate('o100077'))
			;

		$rentalContainer->addText('price', 'o100078')
			->setOption('append', $this->country->defaultCurrency->iso.' '.$this->translate('o100004'))
			->setOption('help', $this->translate('o100073'))
			->addRule(self::INTEGER, $this->translate('o100105'))
			->addRule(self::RANGE, $this->translate('o100105'), [0, 999999999999999])
			;

		$amenityPets = $this->amenityRepository->findByAnimalTypeForSelect($this->getTranslator(), $this->collator);
		$rentalContainer->addSelect('pet', 'o100079', $amenityPets)
			->setPrompt('o854')
			->setRequired($this->translate('o100108'))
			//->setOption('help', $this->translate('o5956'))
			;

		$amenityBoard = $this->amenityRepository->findByBoardTypeForSelect($this->getTranslator(), $this->collator);
		$rentalContainer->addMultiOptionList('board', 'o100080', $amenityBoard)
			->addRule(self::FILLED, $this->translate('o100109'))
			//->setOption('help', $this->translate('o5956'))
			;

		$amenityImportant = $this->amenityRepository->findImportantForSelect($this->getTranslator(), $this->collator);
		$rentalContainer->addMultiOptionList('important', 'o100081', $amenityImportant)
			//->setOption('help', $this->translate('o5956'))
			;

		$amenityAvailability = $this->amenityRepository->findByAvailabilityTypeForSelect($this->getTranslator(), $this->collator);
		$rentalContainer->addSelect('ownerAvailability', 'o100082', $amenityAvailability)
			->setPrompt('o854')
			->setRequired($this->translate('o100107'));
			//->setOption('help', $this->translate('o5956'))
			;

		$rentalContainer->addRentalPhotosContainer('photos');

		$this->addSubmit('submit', $this->translate('o100083'));

		$this->onValidate[] = callback($this, 'validation');
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
				'type' => [
					'type' => 3,
					'classification' => 2,
				],
				'pet' => [1],

				'address' => [
					'address' => 'Ľ. Štúra 8, Nové Zámky, Slovakia',
				],
			],
		];
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

		$phone = $values->phone;
		if(!$phone->phone instanceof \Entity\Contact\Phone) {
			$form['phone']->getMainControl()->addError($this->translate('o1039'));
		}

		$rentalValues = $values->rental;
		$photosSort = $rentalValues->photos->sort;
		if(count($photosSort) < 3) {
			$form['rental']['photos']->getMainControl()->addError($this->translate('o100111'));
		}

	}


}


interface IRegistrationFormFactory {
	/**
	 * @param \Entity\Location\Location $country
	 * @param \Nette\Application\UI\Presenter $presenter
	 *
	 * @return RegistrationForm
	 */
	public function create(\Entity\Location\Location $country, Nette\Application\UI\Presenter $presenter);
}


<?php 

namespace OwnerModule\Forms;

use Entity\Rental\Rental;
use Nette\Forms\Form;

class RentalEditForm extends BaseForm {
	
	protected $languageRepository;
	protected $locationRepository;
	protected $rentalTypeRepository;
	protected $locationTypeRepository;
	protected $rentalAmenityRepository;
	protected $rentalAmenityTypeRepository;

	protected $rental;


	public function __construct(Rental $rental, \Doctrine\ORM\EntityManager $em){
		$this->rental = $rental;
		$this->languageRepository = $em->getRepository('\Entity\Language');
		$this->locationRepository = $em->getRepository('\Entity\Location\Location');
		$this->rentalTypeRepository = $em->getRepository('\Entity\Rental\Type');
		$this->locationTypeRepository = $em->getRepository('\Entity\Location\Type');
		$this->rentalAmenityRepository = $em->getRepository('\Entity\Rental\Amenity');
		$this->rentalAmenityTypeRepository = $em->getRepository('\Entity\Rental\AmenityType');
		parent::__construct();
	}


	public function buildForm() {

		$allCountries = array();
		$phonePrefixes = array();
		$locationType = $this->locationTypeRepository->findOneBySlug('country');
		foreach ($this->locationRepository->findBy(array('type' => $locationType), array('iso'=>'ASC')) as $location) {
			$language = $this->rental->editLanguage;
			$allCountries[$location->iso] = $location->name->getTranslationText($language, TRUE);
			if ($location->phonePrefix) $phonePrefixes[$location->iso] = strtoupper($location->iso) . ' (+'.$location->phonePrefix.')';
		}
		$allCountries = array_reverse($allCountries);


		/*
		 * FORM
		 * Contact info
		 */

		$formName = $this->addText('user','_o962');
		$formName->setAttribute('class', 'testclass');
		$formName->setAttribute('placeholder', 'Meno Priezvysko');

		$formEmail = $this->addText('email','_o965');				
		$formEmail->setAttribute('placeholder', 'example@domain.com');
		$formEmail->setValue($this->rental->user->login);

		$formPhonePresets1 = $this->addSelect('phonePresets1', '', $phonePrefixes);
		$formPhonePresets2 = $this->addSelect('phonePresets2', '', $phonePrefixes);

		$formPhone1 = $this->addText('phone1','_o968');
		$formPhone1->setAttribute('placeholder','09XX XXX XXX');

		$formPhone2 = $this->addText('phone2','_o971');
		$formPhone2->setAttribute('placeholder','09XX XXX XXX');

		$formSite = $this->addText('siteWWW','_o977');
		$formSite->setAttribute('placeholder','www.neco.co');		


		/*
		 * Rental photos
		 * @todo: dorobit form pre fotky
		 */

		$formPhotos;


		/*
		 * Rental location
		 */

		$formLatitude = $this->addText('lat');

		$formLongitude = $this->addText('lng');		
	
		$formLocation1 = $this->addText('location1');
		$formLocation2 = $this->addText('location2');
		$formLocation3 = $this->addTextArea('location3');
		$formLocation4 = $this->addText('location4');



		/*
		 * Rental basic info
		 */

		$formCountry = $this->addSelect('country', 'Země:', $allCountries);
		$formCountry->setPrompt('Zvolte zemi');
		$formCountry->setAttribute('class','span9');

		$basicInfo = $this->addContainer('basicInfo');
		$languages = $this->languageRepository->findSupported();
		foreach ($languages as $language) {

			$rentalTypes = array();
			foreach ($this->rentalTypeRepository->findAll() as $entity) {
				$rentalTypes[$entity->id] = $entity->name->getTranslationText($language, TRUE);
			}

			$formContainer = $basicInfo->addContainer($language->iso);
			$formContainer->addText('name', '_o886');
			$formContainer->addSelect('type', 'Rental type', $rentalTypes);
			$formContainer->addText('teaser', 'Rental teaser');

		}


		/*
		 * Rental Amenities
		 */

		// Amenities
		foreach ($this->rentalAmenityTypeRepository->findAll() as $amenityType) {
			$container = $this->addContainer(str_replace('-', '_', $amenityType->slug));
			foreach ($this->rentalAmenityRepository->findByType($amenityType) as $amenity) {
				$container->addCheckbox($amenity->id, $amenity->name->getTranslationText($this->rental->editLanguage, TRUE));
			}
		}


		/*
		 * Check in & Check out
		 */
		$time = array();
		for ($i=0; $i < 24; $i++) { 
			$time[$i] = $i.':00';
		}
		$checkIn = $this->addSelect('checkin', 'Prichod', $time);
		$checkOut = $this->addSelect('checkout', 'Odchod', $time);
		
		// Owner-availability
		$ownerAvailability = $this->addCheckbox('ownerAvailability', 'Je možné ubytovať viaceré nezávislé skupiny naraz.');

		// Price upon Request
		$ownerAvailability = $this->addCheckbox('priceUponRequest', '');

		/*
		 * Rental Interview
		 */

		$formInterview1 = $this->addTextArea('interview1');
		$formInterview1->setAttribute('class','marginBottom');

		$formInterview2 = $this->addTextArea('interview2');
		$formInterview2->setAttribute('class','marginBottom');

		$formInterview3 = $this->addTextArea('interview3');
		$formInterview1->setAttribute('class','marginBottom');

		$formInterview4 = $this->addTextArea('interview4');
		$formInterview4->setAttribute('class','marginBottom');

		$formInterview5 = $this->addTextArea('interview5');
		$formInterview5->setAttribute('class','marginBottom');


		/*
		 * Form agree, submit
		 */

		$formAgree = $this->addCheckbox('agree', 'Souhlasím s podmínkami');
		$formAgree->addRule(Form::EQUAL, 'Je potřeba souhlasit s podmínkami', TRUE);

		$formSubmit = $this->addButton('sbumit', '_o985');
		$formSubmit->setAttribute('onclick', 'send()');
		$formSubmit->setAttribute('class','btn btn-green marginBottom');

	}

	public function setDefaultsValues()
	{

	}

}
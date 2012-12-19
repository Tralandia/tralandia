<?php 

namespace OwnerModule\Forms;

use Entity\Rental\Rental;
use Nette\Forms\Form;

class RentalEditForm extends BaseForm {
	
	protected $languageRepositoryAccessor;
	protected $locationRepositoryAccessor;
	protected $rentalTagRepositoryAccessor;
	protected $rentalTypeRepositoryAccessor;
	protected $locationTypeRepositoryAccessor;
	protected $rentalAmenityRepositoryAccessor;
	protected $rentalAmenityTypeRepositoryAccessor;

	protected $phraseDecoratorFactory;

	protected $rental;

	public function injectBaseRepositories(\Nette\DI\Container $dic) {
		$this->languageRepositoryAccessor = $dic->languageRepositoryAccessor;
		$this->locationRepositoryAccessor = $dic->locationRepositoryAccessor;
		$this->rentalTagRepositoryAccessor = $dic->rentalTagRepositoryAccessor;
		$this->rentalTypeRepositoryAccessor = $dic->rentalTypeRepositoryAccessor;
		$this->locationTypeRepositoryAccessor = $dic->locationTypeRepositoryAccessor;
		$this->rentalAmenityRepositoryAccessor = $dic->rentalAmenityRepositoryAccessor;
		$this->rentalAmenityTypeRepositoryAccessor = $dic->rentalAmenityTypeRepositoryAccessor;
	}

	public function injectPhraseService(\Model\Phrase\IPhraseDecoratorFactory $phraseDecoratorFactory) {
		$this->phraseDecoratorFactory = $phraseDecoratorFactory;
	}

	public function __construct(Rental $rental){
		$this->rental = $rental;
		parent::__construct();
	}


	public function buildForm() {

		$allCountries = array();
		$phonePrefixes = array();
		$locationType = $this->locationTypeRepositoryAccessor->get()->findOneBySlug('country');
		foreach ($this->locationRepositoryAccessor->get()->findBy(array('type' => $locationType), array('iso'=>'ASC')) as $location) {
			$phrase = $this->phraseDecoratorFactory->create($location->name);
			$language = $this->rental->editLanguage;
			$allCountries[$location->iso] = $phrase->getTranslationText($language, TRUE);
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
		$languages = $this->languageRepositoryAccessor->get()->findSupported();
		foreach ($languages as $language) {

			$rentalTypes = array();
			foreach ($this->rentalTypeRepositoryAccessor->get()->findAll() as $entity) {
				$phrase = $this->phraseDecoratorFactory->create($entity->name);
				$rentalTypes[$entity->id] = $phrase->getTranslationText($language, TRUE);
			}

			$formContainer = $basicInfo->addContainer($language->iso);
			$formContainer->addText('name', '_o886');
			$formContainer->addSelect('type', 'Rental type', $rentalTypes);
			$formContainer->addText('teaser', 'Rental teaser');

		}


		/*
		 * Rental Amenities & Tags
		 */

		// Tags
		$tagsContainer = $this->addContainer('tags');
		foreach ($this->rentalTagRepositoryAccessor->get()->findAll() as $tag) {
			$phrase = $this->phraseDecoratorFactory->create($tag->name);
			$tagsContainer->addCheckbox($tag->id, $phrase->getTranslationText($this->rental->editLanguage, TRUE));
		}

		// Amenities
		foreach ($this->rentalAmenityTypeRepositoryAccessor->get()->findAll() as $amenityType) {
			$container = $this->addContainer(str_replace('-', '_', $amenityType->slug));
			foreach ($this->rentalAmenityRepositoryAccessor->get()->findByType($amenityType) as $amenity) {
				$phrase = $this->phraseDecoratorFactory->create($amenity->name);
				$container->addCheckbox($amenity->id, $phrase->getTranslationText($this->rental->editLanguage, TRUE));
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

}
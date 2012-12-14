<?php 

namespace OwnerModule\Forms;

use Entity\Rental\Rental;
use Nette\Forms\Form;

class RentalEditForm extends BaseForm {
	
	protected $rentalTypeRepositoryAccessor;
	protected $rentalTagRepositoryAccessor;
	protected $languageRepositoryAccessor;

	protected $phraseDecoratorFactory;

	protected $rental;

	public function injectBaseRepositories(\Nette\DI\Container $dic) {
		$this->rentalTypeRepositoryAccessor = $dic->rentalTypeRepositoryAccessor;
		$this->rentalTagRepositoryAccessor = $dic->rentalTagRepositoryAccessor;
		$this->languageRepositoryAccessor = $dic->languageRepositoryAccessor;
	}

	public function injectPhraseService(\Model\Phrase\IPhraseDecoratorFactory $phraseDecoratorFactory) {
		$this->phraseDecoratorFactory = $phraseDecoratorFactory;
	}

	public function __construct(Rental $rental){
		$this->rental = $rental;
		parent::__construct();
	}


	public function buildForm() {

		/*
		 * Contact info
		 */

		$formName = $this->addText('user','_o962');
		$formName->setAttribute('class', 'testclass');
		$formName->setAttribute('placeholder', 'Meno Priezvysko');

		$formEmail = $this->addText('email','_o965');				
		$formEmail->setAttribute('placeholder', 'example@domain.com');
		$formEmail->setValue($this->rental->user->login);

		$phonePresets = array('+421','+420','+5694');

		$formPhonePresets1 = $this->addSelect('phonePresets1', '', $phonePresets);
		$formPhonePresets2 = $this->addSelect('phonePresets2', '', $phonePresets);

		$formPhone1 = $this->addText('phone1','_o968');
		$formPhone1->setAttribute('placeholder','09XX XXX XXX');

		$formPhone2 = $this->addText('phone2','_o971');
		$formPhone2->setAttribute('placeholder','09XX XXX XXX');

		$formSkype = $this->addText('skype','_o972');
		$formSkype->setAttribute('placeholder','skype.name');

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

		$countries = array(
			'Europe' => array(
				'CZ' => 'Česká Republika',
				'SK' => 'Slovensko',
				'GB' => 'Velká Británie',
			),
			'CA' => 'Kanada',
			'US' => 'USA',
			'?'  => 'jiná',
		);

		$formCountry = $this->addSelect('country', 'Země:', $countries);
		$formCountry->setPrompt('Zvolte zemi');
		$formCountry->setAttribute('class','span9');	


		/*
		 * Rental basic info
		 */

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
		$tagsContainer = $this->addContainer('tags');
		$rentalTags = $this->rentalTagRepositoryAccessor->get()->findAll();
		foreach ($rentalTags as $tag) {
			$phrase = $this->phraseDecoratorFactory->create($tag->name);
			$tagsContainer->addCheckbox($tag->id, $phrase->getTranslationText($this->rental->editLanguage, TRUE));
		}


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

		$this->addTextArea('desc','desc');

		$formGender = $this->addRadioList('gender', 'Pohlaví:', array(
			'm' => 'muž',
			'f' => 'žena',
		));


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
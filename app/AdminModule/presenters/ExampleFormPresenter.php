<?php

namespace AdminModule;

use Nette;
use Extras;
use Nette\Application\UI\Form;

class ExampleFormPresenter extends BasePresenter {

	protected $phraseRepositoryAccessor;

	public function injectDic(\Nette\DI\Container $dic) {
		$this->phraseRepositoryAccessor = $dic->phraseRepositoryAccessor;
	}

	public function actionDefault() {

	}

	public function createComponentBaseForm()
	{
		$form = new Form;
		$form->setRenderer(new Extras\Forms\Rendering\DefaultRenderer);
		$form->getElementPrototype()->novalidate = 'novalidate';


		$items = ['yes', 'maybe', 'no'];
		$manyItems = ['Boronia', 'Bighead knapweed', 'Bouvardia', 'Button Funray', 'Billy buttons', 'Breath of heaven - Diosma', 'Blue lace flower', 'Babys breath', 'Bells of Ireland', 'Boxwood african', 'Buckthorn variegated', 'Bird of paradise', 'Common Yarrow', 'Carnation mini - Alibi', 'Cockscomb crested', 'Cosmos', 'Curcuma', 'Cymbidium orchid'];
		$multiItems = [
			'Marsupials' => [1 => 'Koala', 'Red kangaroo', 'Yellow-footed rock wallaby', 'Dama wallaby ',
				'Parma wallaby'],
			'Primates' => [100 => 'Lowland gorilla', 'Chimpanzee', 'Chimpanzee II', 'Chimpanzee III', 'Bonobo', 'White-cheeked gibbon', 'Orangutan', 'Mandrill baboon', 'Spider monkey', 'Colobus monkey'],
			'Small Mammals' => [200 => 'Prairie dog', 'Fruit bat', 'Long-nosed bat', 'American porcupine',
				'Nine banded armadillo'],
		];
		$phrase = $this->phraseRepositoryAccessor->get()->find(1);

		// -------------------- FIELDS --------------------
		$form->addText('text', 'Text')
			->setOption('description', 'this is description')
			->setRequired('toto je povinne!')
			->getControlPrototype()
				->setPlaceholder('Text');


		$form->addAdvancedPrice('price' , 'Price', ['EUR', 'HUF', 'CZK']);

/*
		$form->addTextArea('textArea', 'Text Area')
			->setRequired();

		$form->addAdvancedTinymce('tinymce', 'TinyMce');

		$form->addSelect('select', 'Select', $items)
			->setPrompt('----')
			->setRequired();

		$form->addSelect('select2', 'Select 2', $multiItems)
			->setPrompt('----')
			->setRequired();

		$form->addMultiSelect('multiSelect', 'Multi select', $items, 5)
			->setRequired();

		$form->addMultiSelect('multiSelect2', 'Multi select 2', $multiItems, 5)
			->setRequired();

		$form->addAdvancedCheckbox('checkbox', 'Checkbox')
			->setRequired();

		$form->addAdvancedCheckboxList('checkboxLis', 'Checkbox List', $items)
			->setRequired();

		$form->addAdvancedCheckboxList('checkboxLis2', 'Checkbox List 2', $manyItems)
			->setRequired();

		$form->addAdvancedDatePicker('datePicker', 'Date pricker');

		$form->addAdvancedAddress('address', 'Address')
			->setCountryItems(['sk', 'hu', 'cz']);

		$form->addAdvancedBricksList('brickList', 'Brick list', $items)
			->setDefaultParam($items);

		$form->addAdvancedFileManager('fileManager', 'File manager', $items);

		$form->addAdvancedUpload('upload', 'Upload');

		$form->addAdvancedJson('json', 'Json', ['lvl1' => ['lvl1.1', 'lvl1.2'], 'lvl2', 'lvl3' => 'lvl3.1']);

		$form->addAdvancedNeon('neon', 'Neon');

		$form->addAdvancedTable('table', 'Table', ['EUR', 'HUF', 'CZK'], 3);

		$form->addAdvancedPhrase('phrase', 'Phrase')
			->setPhrase($phrase);

		$form->addReadOnlyPhrase('readOnlyPhrase', 'Read Only Phrase')
			->setPhrase($phrase);
*/


		// -------------------- BUTTONS --------------------
		$form->addSubmit('submit', 'Submit');

		$form->addSubmit('refresh', 'Refresh')
			->setValidationScope(FALSE)
			->onClick[] = array($this, 'refreshBaseFrom');

		$form->onSuccess[] = array($this, 'processBaseForm');

		return $form;
	}

	public function processBaseForm(Form $form)
	{
		$values = $form->getValues();
		d($values);
	}

	public function refreshBaseFrom(\Nette\Forms\Controls\Button $button) {
		$this->redirect('this');
	}


}

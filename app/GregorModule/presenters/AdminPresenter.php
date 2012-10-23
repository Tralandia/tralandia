<?php

namespace GregorModule;

class AdminPresenter extends BasePresenter {

	protected function createComponentForm()
	{
		$form = new \AdminModule\Forms\AdminForm;

		$items = array(
			1 => 'Item 1',
			2 => 'Item 2',
			3 => 'Item 3',
		);

		$form->addAdvancedAddress('address', 'Address', $items);
		$form->addAdvancedBricksList('bricksList', 'BricksList', $items)->setDefaultParam($items);
		$form->addAdvancedCheckbox('checkbox', 'Checkbox');
		$form->addAdvancedCheckbox('checkbox2', 'Checkbox')->setDefaultValue(TRUE);
		$form->addAdvancedDatePicker('advancedDatePicker', 'Date Picker')->setDefaultValue(new \Nette\DateTime);
	
		$form->addSubmit('sub');
		
		return $form;
	}


}
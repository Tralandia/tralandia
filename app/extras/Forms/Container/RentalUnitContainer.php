<?php

namespace Extras\Forms\Container;

use Extras\Books\Phone;
use Nette\Localization\ITranslator;

class RentalUnitContainer extends BaseContainer
{

	/**
	 * @var \Entity\User\User
	 */
	protected $user;

	/**
	 * @var \Nette\Localization\ITranslator $translator
	 */
	protected $translator;

	/**
	 * @var array
	 */
	protected $units;


	/**
	 * @param \Nette\ComponentModel\IContainer $label
	 * @param null $phonePrefixes
	 * @param \Extras\Books\Phone $phoneBook
	 * @param \Nette\Localization\ITranslator $translator
	 */
	public function __construct($label, $user, ITranslator $translator)
	{
		$this->translator = $translator;
		$this->user = $user;
		parent::__construct();

		$units = array();
		$rentalOptions = array();
		foreach ($this->user->getRentals() as $rental) {
			$rentalOptions[$rental->id] = $this->translator->translate($rental->getName());
			$units[$rental->id] = $rental->getUnits();
		}

		$this->addSelect('rental', $label, $rentalOptions)
			->setPrompt('')
			->setRequired('Povinne policko');

		foreach ($units as $rentalId => $options) {
			$this->units[$rentalId] = $this->addMultiOptionList('unit'.$rentalId, NULL, $options);
		}

	}


	public function getUnitsControl()
	{
		return $this->units;
	}


	public function getRentalControl()
	{
		return $this['rental'];
	}


	public function setValues($values, $erase = FALSE)
	{
		// if (!$values) return NULL;

		// if ($values instanceof \Entity\Contact\Phone) {
		// 	$valuesTemp = [];
		// 	$valuesTemp['prefix'] = $values->getPrimaryLocation()->getPhonePrefix();
		// 	// $valuesTemp['number'] = trim(str_replace('+' . $valuesTemp['prefix'], '', $values->getInternational()));
		// 	$values = $valuesTemp;
		// }
		// parent::setValues($values, $erase);
	}


	public function getFormattedValues($asArray = FALSE)
	{
		// $number = $this['number']->getValue();

		// if($number) {
		// 	// $phone = $this['prefix']->getValue() . $this['number']->getValue();
		// 	$phone = $this->phoneBook->getOrCreate($phone, $this['prefix']->getValue());
		// } else {
		// 	$phone = NULL;
		// }

		// $values = $asArray ? array() : new \Nette\ArrayHash;
		// $values['prefix'] = $this['prefix']->getValue();
		// // $values['number'] = $this['number']->getValue();
		// $values['entity'] = $phone;

		// return $values;
	}


	public function getMainControl()
	{
		return $this->getRentalControl();
	}

	public function validate(array $controls = NULL) {
		// $values = $this->getFormattedValues();
		// if ($values->entity === FALSE || !$values->number) {
		// 	$this->getMainControl()->addError($this->translator->translate('151882'));
		// }
	}

}

<?php

namespace Extras\Forms\Container;

use Extras\Books\Phone;
use Nette\Localization\ITranslator;

class PhoneContainer extends BaseContainer
{

	/**
	 * @var \Extras\Books\Phone
	 */
	protected $phoneBook;

	/**
	 * @var \Nette\Localization\ITranslator $translator
	 */
	protected $translator;


	/**
	 * @param \Nette\ComponentModel\IContainer $label
	 * @param null $phonePrefixes
	 * @param \Extras\Books\Phone $phoneBook
	 * @param \Nette\Localization\ITranslator $translator
	 */
	public function __construct($label, $phonePrefixes, Phone $phoneBook, ITranslator $translator)
	{
		$this->phoneBook = $phoneBook;
		$this->translator = $translator;
		parent::__construct();

		$this->addSelect('prefix', NULL, $phonePrefixes);

		//$numberErrorValidateMessage = (!$translator->translate('151882')) ? ' ' : $translator->translate('151882');

		$this->addText('number', $label)
			->setOption('help', $translator->translate('o1038'));
			//->setRequired($numberErrorValidateMessage); toto je tu naschal zakomentovane, nie stale chceme mat cislo povinne

	}


	public function getPrefixControl()
	{
		return $this['prefix'];
	}


	public function getNumberControl()
	{
		return $this['number'];
	}


	public function setValues($values, $erase = FALSE)
	{
		if (!$values) return NULL;

		if ($values instanceof \Entity\Contact\Phone) {
			$valuesTemp = [];
			$valuesTemp['prefix'] = $values->getPrimaryLocation()->getPhonePrefix();
			$valuesTemp['number'] = trim(str_replace('+' . $valuesTemp['prefix'], '', $values->getInternational()));
			$values = $valuesTemp;
		}
		parent::setValues($values, $erase);
	}


	public function getFormattedValues($asArray = FALSE)
	{
		$number = $this['number']->getValue();

		if($number) {
			$phone = $this['prefix']->getValue() . $this['number']->getValue();
			$phone = $this->phoneBook->getOrCreate($phone, $this['prefix']->getValue());
		} else {
			$phone = NULL;
		}

		$values = $asArray ? array() : new \Nette\ArrayHash;
		$values['prefix'] = $this['prefix']->getValue();
		$values['number'] = $this['number']->getValue();
		$values['entity'] = $phone;

		return $values;
	}


	public function getMainControl()
	{
		return $this->getNumberControl();
	}

	public function validate() {
		$values = $this->getFormattedValues();
		if ($values->entity === FALSE) {
			$this->getMainControl()->addError($this->translator->translate('151882'));
		}
	}

}

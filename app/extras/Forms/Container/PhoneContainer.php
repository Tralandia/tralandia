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
	 * @param \Nette\ComponentModel\IContainer $label
	 * @param null $phonePrefixes
	 * @param \Extras\Books\Phone $phoneBook
	 * @param \Nette\Localization\ITranslator $translator
	 */
	public function __construct($label, $phonePrefixes, Phone $phoneBook, ITranslator $translator)
	{
		$this->phoneBook = $phoneBook;
		parent::__construct();

		$this->addSelect('prefix', NULL, $phonePrefixes);

		$this->addText('number', $label)
			->setOption('help', $translator->translate('o1038'));

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
			$valuesTemp['number'] = str_replace('+' . $valuesTemp['prefix'], '', $values->getInternational());
			$values = $valuesTemp;
		}
		parent::setValues($values, $erase);
	}


	public function getFormattedValues($asArray = FALSE)
	{

		$phone = $this['prefix']->getValue() . $this['number']->getValue();
		$phone = $this->phoneBook->getOrCreate($phone);

		$values = $asArray ? array() : new \Nette\ArrayHash;
		$values['prefix'] = $this['prefix']->getValue();
		$values['number'] = $this['number']->getValue();
		$values['entity'] = $phone;

		return $values;
	}


	public function getMainControl()
	{
		return $this['number'];
	}

}

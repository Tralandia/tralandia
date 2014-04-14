<?php

namespace Extras\Forms\Container;

use Extras\Books\Phone;
use Nette\Localization\ITranslator;

class RentalUnitContainer extends BaseContainer
{

	/**
	 * @var \Nette\Localization\ITranslator $translator
	 */
	protected $translator;

	/**
	 * @var array
	 */
	protected $rentals;

	/**
	 * @var array
	 */
	protected $units;


	/**
	 * @param \Nette\ComponentModel\IContainer $label
	 * @param \Nette\Localization\ITranslator $translator
	 */
	public function __construct($label, $rentals, ITranslator $translator)
	{
		$this->translator = $translator;
		parent::__construct();

		$this->addText('mainControl');
		$this->rentals = $rentals;
		foreach ($rentals as $rental) {
			$rentalId = $rental->getId();
			$options = \Tools::entitiesMap($rental->getUnits(), 'id', 'name');
			$this->units[$rentalId] = $this->addMultiOptionList('unit'.$rentalId, NULL, $options);
		}

	}


	public function getUnitsControl()
	{
		return $this->units;
	}


	public function getRentals()
	{
		return $this->rentals;
	}


	public function setValues($values, $erase = FALSE)
	{
		 if (!$values) return NULL;

		 if (is_array($values)) {
			 $temp = [];
			 foreach($values as $value) {
				 if($value instanceof \Entity\Rental\Unit)
				 $temp['unit' . $value->getRental()->getId()][] = $value->getId();
			 }
			 $values = $temp;
		 }
		 parent::setValues($values, $erase);
	}


	public function getFormattedValues($asArray = FALSE)
	{
		$values = [];

		foreach($this->getUnitsControl() as $unitControl) {
			$unitValues = $unitControl->getValue($asArray);
			if(count($unitValues)) {
				$values = array_merge($values, $unitValues);
			}
		}

		return $values;
	}


	public function getMainControl()
	{
		return $this['mainControl'];
	}

}

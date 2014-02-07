<?php

namespace Extras\Forms\Container;


use Kdyby\Doctrine\EntityDao;
use Nette\Localization\ITranslator;
use Nette\Utils\Html;
use Entity\Rental\Rental;
use Repository\Rental\TypeRepository;

class RentalTypeContainer extends BaseContainer
{


	/**
	 * @var \Entity\Rental\Rental
	 */
	protected $rental;

	/**
	 * @var \Kdyby\Doctrine\EntityDao
	 */
	private $rentalTypeDao;


	/**
	 * @param \Entity\Rental\Rental $rental
	 * @param array $rentalTypes
	 * @param ITranslator $translator
	 * @param \Kdyby\Doctrine\EntityDao $rentalTypeDao
	 */
	public function __construct(Rental $rental = NULL, array $rentalTypes, ITranslator $translator, EntityDao $rentalTypeDao)
	{
		parent::__construct();

		$this->rentalTypeDao = $rentalTypeDao;
		$this->rental = $rental;

		$typesOptions = [];
		$elTemplate = Html::el('option');

		foreach($rentalTypes as $typeId => $type) {
			$el = clone $elTemplate;
			$el->value($typeId)->setText($type['label']);
			$el->addAttributes(['data-classification' => $type['entity']->hasClassification()]);
			$typesOptions[$typeId] = $el;
		}

		$this->addSelect('type', '151884', $typesOptions)
			//->setOption('help', $this->translate('o5956'))
			->setPrompt('o854')
			->setRequired($translator->translate('1287'))
		;
		// @todo prerobit ★ na html znak
		$this->addSelect('classification', 'o25137', array(1 => '★', '★ ★', '★ ★ ★', '★ ★ ★ ★', '★ ★ ★ ★ ★'))
			->setPrompt('--')
//			->setRequired($translator->translate('o100104'))
			//->setOption('help', $this->translate('o5956'))
		;

		$this->setDefaultValues();

	}

	public function setDefaultValues()
	{
		$defaults = [];
		if($this->rental) {
			$type = $this->rental->getType();
			$defaults = [
				'type' => $type ? $type->getId() : NULL,
				'classification' => $this->rental->getClassification()
			];
		}
		return $this->setDefaults($defaults);
	}

	public function getFormattedValues($asArray = FALSE)
	{
		$values = $this->getValues($asArray);
		$values['type'] = $this->rentalTypeDao->find($values['type']);
		return $values;
	}

	public function getMainControl()
	{
		return $this['type'];
	}

}

<?php

namespace Extras\Forms\Container;


use Nette\Localization\ITranslator;
use Nette\Utils\Html;
use Repository\Rental\TypeRepository;

class RentalTypeContainer extends BaseContainer
{

	/**
	 * @var \Repository\Rental\TypeRepository
	 */
	protected $rentalTypeRepository;


	/**
	 * @param array $rentalTypes
	 * @param ITranslator $translator
	 * @param $rentalTypeRepository
	 */
	public function __construct(array $rentalTypes, ITranslator $translator, TypeRepository $rentalTypeRepository)
	{
		parent::__construct();

		$this->rentalTypeRepository = $rentalTypeRepository;

		$typesOptions = [];
		$elTemplate = Html::el('option');

		foreach($rentalTypes as $typeId => $type) {
			$el = clone $elTemplate;
			$el->value($typeId)->setText($type['label']);
			$el->addAttributes(['data-classification' => $type['entity']->hasClassification()]);
			$typesOptions[$typeId] = $el;
		}

		$this->addSelect('type', 'o883', $typesOptions)
			//->setOption('help', $this->translate('o5956'))
			->setPrompt('o854')
		;
		// @todo prerobit ★ na html znak
		$this->addSelect('classification', 'o25137', array('★', '★ ★', '★ ★ ★', '★ ★ ★ ★', '★ ★ ★ ★ ★'))
			->setRequired($translator->translate('o100104'))
			//->setOption('help', $this->translate('o5956'))
		;

	}

	public function getValues($asArray = FALSE)
	{
		$values = $asArray ? array() : new \Nette\ArrayHash;
		foreach ($this->getComponents() as $name => $control) {
			if ($name=='type') {
				$controlValue = $control->getValue();
				$values[$name] = $controlValue ? $this->rentalTypeRepository->find($controlValue) : NULL;
			}
			if ($name=='classification') {
				$values[$name] = $control->getValue();
			}
		}
		return $values;
	}

	public function getMainControl()
	{
		return $this['type'];
	}

}

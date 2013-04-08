<?php

namespace Extras\Forms\Container;


use Nette\Localization\ITranslator;
use Nette\Utils\Html;

class RentalTypeContainer extends BaseContainer
{

	/**
	 * @param array $rentalTypes
	 * @param \Nette\Localization\ITranslator $translator
	 */
	public function __construct(array $rentalTypes, ITranslator $translator)
	{
		parent::__construct();

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
		;
		// @todo prerobit ★ na html znak
		$this->addSelect('classification', 'o25137', array('★', '★ ★', '★ ★ ★', '★ ★ ★ ★', '★ ★ ★ ★ ★'))
			->setRequired($translator->translate('o100104'))
			//->setOption('help', $this->translate('o5956'))
		;

	}

	public function getMainControl()
	{
		return $this['type'];
	}

}

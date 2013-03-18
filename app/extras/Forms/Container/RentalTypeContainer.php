<?php

namespace Extras\Forms\Container;


use Nette\Utils\Html;

class RentalTypeContainer extends BaseContainer
{

	/**
	 * @var array
	 */
	protected $months = [];

	/**
	 * @param array $rentalTypes
	 */
	public function __construct(array $rentalTypes)
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
			//->setOption('help', $this->translate('o5956'))
		;

	}

	public function getMainControl()
	{
		return $this['type'];
	}

}

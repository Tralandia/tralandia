<?php

namespace Extras\Forms\Container;

use Environment\Collator;
use Extras\Types\Price;
use Doctrine\ORM\EntityManager;
use Nette\Localization\ITranslator;
use Nette\Forms\Form;

class PriceContainer extends BaseContainer
{
	/**
	 * @var \Repository\Rental\TypeRepository
	 */
	protected $currencyRepository;


	/**
	 * @param string|null $label
	 * @param EntityManager $em
	 * @param ITranslator $translator
	 * @param \Environment\Collator $collator
	 */
	public function __construct($label = NULL, EntityManager $em, ITranslator $translator, Collator $collator)
	{
		parent::__construct();

		$this->currencyRepository = $em->getRepository(CURRENCY_ENTITY);

		$this->addText('amount', $label)
			->setOption('help', $translator->translate('o100073'))
			->addRule(Form::RANGE, $translator->translate('o100105'), [0, 999999999999999]);

		$currencies = $this->currencyRepository->getForSelect($translator, $collator);
		$this->addSelect('currency', NULL, $currencies);
	}

	/**
	 * Fill-in with values.
	 * @param  array|Traversable  values used to fill the form
	 * @param  bool     erase other controls?
	 * @return Container  provides a fluent interface
	 */
	public function setValues($values, $erase = FALSE)
	{
		if(!$values) return NULL;

		if($values instanceof Price) {
			$valuesTemp = [];
			$valuesTemp['amount'] = $values->getSourceAmount();
			$valuesTemp['currency'] = $values->getSourceCurrency()->getId();
			$values = $valuesTemp;
		}
		return parent::setValues($values, $erase);
	}


	/**
	 * Returns the formatted values submitted by the form.
	 * @param  bool  return values as an array?
	 * @return \Nette\ArrayHash|array
	 */
	public function getFormattedValues($asArray = FALSE)
	{
		$values = $this->getValues($asArray);
		$currency = $this->currencyRepository->find($values['currency']);
		$values['entity'] = new \Extras\Types\Price($values['amount'], $currency);

		return $values;
	}


	public function getMainControl()
	{
		return $this['amount'];
	}
}

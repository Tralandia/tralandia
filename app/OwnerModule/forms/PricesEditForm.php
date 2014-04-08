<?php

namespace OwnerModule\Forms;

use Doctrine\ORM\EntityManager;
use Entity\Rental\CustomPricelistRow;
use Entity\Rental\Pricelist;
use Entity\Rental\Rental;
use Environment\Environment;
use Nette\Localization\ITranslator;
use Tralandia\Currency\Currencies;
use Nette\Utils\Html;

class PricesEditForm extends BaseForm {

	/**
	 * @var array
	 */
	public $onSave = [];


	/**
	 * @var \Entity\Rental\Rental
	 */
	private $rental;

	/**
	 * @var \Environment\Environment
	 */
	private $environment;

	/**
	 * @var \Tralandia\Currency\Currencies
	 */
	private $currencies;

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	private $em;


	/**
	 * @param \Entity\Rental\Rental $rental
	 * @param \Doctrine\ORM\EntityManager $em
	 * @param \Environment\Environment $environment
	 * @param \Tralandia\Currency\Currencies $currencies
	 * @param \Nette\Localization\ITranslator $translator
	 */
	public function __construct(Rental $rental, Environment $environment, EntityManager $em, Currencies $currencies, ITranslator $translator){
		$this->rental = $rental;
		$this->environment = $environment;
		$this->currencies = $currencies;
		parent::__construct($translator);
		$this->em = $em;
	}


	public function buildForm() {
		$currency = $this->rental->getCurrency();

		$this->addSelect('currency', '713', $this->currencies->getForSelect());

		$currencySpan = Html::el('span', array(
			'for' => 'currency'
		));

		$this->addText('price', 'o100078')
			->setOption('append', $currencySpan . ' ' . $this->translate('o100004'))
			->setOption('help', $this->translate('o100073'))
			->addRule(self::RANGE, $this->translate('o100105'), [0, 999999999999999])
			->setRequired('151883');

		$this->addRentalPriceListContainer('customPriceList', $this->rental);

		$this->addRentalPriceUploadContainer('priceUpload', $this->rental);

		$this->addSubmit('submit', 'o100151');

		$this->onSuccess[] = [$this, 'process'];

		$this->onAttached[] = function(\Nette\Application\UI\Form $form) {
			$form['customPriceList']->setDefaultsValues();
			$form['priceUpload']->setDefaultsValues();
		};

	}

	public function setDefaultsValues()
	{
		$rental = $this->rental;
		$defaults = [
			'currency' => $rental->getCurrency()->getId(),
			'price' => $rental->getPrice()->getSourceAmount(),
		];
		$this->setDefaults($defaults);
	}

	public function process(PricesEditForm $form)
	{
		$values = $form->getFormattedValues();

		$rental = $this->rental;

		$currency = $this->em->getRepository(CURRENCY_ENTITY)->find($values['currency']);
		$rental->setCurrency($currency);

		$rental->setFloatPrice($values['price']);

		if ($value = $values['customPriceList']) {
			foreach ($value['list'] as $pricelistRow) {
				if ($pricelistRow->entity instanceof CustomPricelistRow && !$pricelistRow->entity->getRental()) {
					$rental->addCustomPricelistRow($pricelistRow->entity);
				}
			}
		}

		if ($value = $values['priceUpload']) {
			$priceLists = $rental->getPricelists();
			foreach ($priceLists as $priceList) {
				$rental->removePricelist($priceList);
			}
			foreach ($value['list'] as $priceList) {
				if ($priceList->entity instanceof Pricelist) {
					$rental->addPricelist($priceList->entity);
				}
			}
		}

		$this->em->flush();
		$this->onSave($rental);
	}

}

interface IPricesEditFormFactory
{
	public function create(Rental $rental, Environment $environment);
}

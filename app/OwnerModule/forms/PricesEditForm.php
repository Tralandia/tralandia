<?php

namespace OwnerModule\Forms;

use Entity\Rental\Rental;
use Environment\Environment;
use Nette\Localization\ITranslator;

class PricesEditForm extends BaseForm {

	/**
	 * @var \Entity\Rental\Rental
	 */
	private $rental;

	/**
	 * @var \Environment\Environment
	 */
	private $environment;


	/**
	 * @param \Entity\Rental\Rental $rental
	 * @param \Environment\Environment $environment
	 * @param \Nette\Localization\ITranslator $translator
	 */
	public function __construct(Rental $rental, Environment $environment, ITranslator $translator){
		$this->rental = $rental;
		$this->environment = $environment;
		parent::__construct($translator);
	}


	public function buildForm() {
		$currency = $this->rental->getPrimaryLocation()->getDefaultCurrency();

		$this->addText('price', 'o100078')
			->setOption('append', $currency->getIso() . ' ' . $this->translate('o100004'))
			->setOption('help', $this->translate('o100073'))
			->addRule(self::RANGE, $this->translate('o100105'), [0, 999999999999999])
			->setRequired('151883');

		$this->addRentalPriceListContainer('priceList', $currency, $this->rental);

		$this->addRentalPriceUploadContainer('priceUpload', $this->rental);

		$this->addSubmit('submit', 'o100151');

		$this->onSuccess[] = [$this, 'process'];

		$this->onAttached[] = function(\Nette\Application\UI\Form $form) {
			$form['priceList']->setDefaultsValues();
			$form['priceUpload']->setDefaultsValues();
		};

	}

	public function setDefaultsValues()
	{

	}

	public function process(UserEditForm $form)
	{
		$values = $form->getValues();

	}

}

interface IPricesEditFormFactory
{
	public function create(Rental $rental, Environment $environment);
}

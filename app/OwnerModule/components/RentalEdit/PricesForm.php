<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 11/04/14 08:13
 */

namespace OwnerModule\RentalEdit;


use BaseModule\Forms\BaseForm;
use BaseModule\Forms\ISimpleFormFactory;
use Entity\Rental\CustomPricelistRow;
use Entity\Rental\Pricelist;
use Entity\Rental\Rental;
use Environment\Environment;
use Kdyby\Doctrine\EntityManager;
use Nette;
use Nette\Utils\Html;
use Tralandia\Currency\Currencies;

class PricesForm extends BaseFormControl
{

	public $onFormSuccess = [];

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
	 * @var \BaseModule\Forms\ISimpleFormFactory
	 */
	private $formFactory;


	/**
	 * @param \Entity\Rental\Rental $rental
	 * @param \Environment\Environment $environment
	 * @param \BaseModule\Forms\ISimpleFormFactory $formFactory
	 * @param \Kdyby\Doctrine\EntityManager $em
	 * @param \Tralandia\Currency\Currencies $currencies
	 */
	public function __construct(Rental $rental, Environment $environment, ISimpleFormFactory $formFactory, EntityManager $em, Currencies $currencies){
		parent::__construct();
		$this->rental = $rental;
		$this->environment = $environment;
		$this->currencies = $currencies;
		$this->em = $em;
		$this->formFactory = $formFactory;
	}



	public function createComponentForm()
	{
		$form = $this->formFactory->create();

		$form->addSelect('currency', '713', $this->currencies->getForSelect());

		$currencySpan = Html::el('span', array(
			'for' => 'currency'
		));

		$priceIntervalContainer = $form->addContainer('priceInterval');

		$priceIntervalContainer->addText('priceFrom', $this->translate('1277'))
			->setOption('prepend', $this->translate('1277'))
			->setOption('append', $currencySpan . ' ' . $this->translate('o100004'))
			// ->setOption('help', $this->translate('o100073'))
			->addRule(BaseForm::RANGE, $this->translate('o100105'), [0, 999999999999999])
			->setRequired('151883');

		$priceIntervalContainer->addText('priceTo', $this->translate('1278'))
			->setOption('prepend', $this->translate('1278'))
			->setOption('append', $currencySpan . ' ' . $this->translate('o100004'))
			// ->setOption('help', $this->translate('o100073'))
			->addRule(BaseForm::RANGE, $this->translate('o100105'), [0, 999999999999999])
			->setRequired('151883');

		$form->addRentalPriceListContainer('customPriceList', $this->rental);

		$form->addRentalPriceUploadContainer('priceUpload', $this->rental);

		$form->addSubmit('submit', 'o100151');

		$form->onAttached[] = function(\Nette\Application\UI\Form $form) {
			$form['customPriceList']->setDefaultsValues();
			$form['priceUpload']->setDefaultsValues();
		};

		$form->onValidate[] = $this->validateForm;
		$form->onSuccess[] = $this->processForm;

		$this->setDefaults($form);

		return $form;
	}


	public function setDefaults(BaseForm $form)
	{
		$rental = $this->rental;
		$defaults = [
			'currency' => $rental->getCurrency()->getId(),
			'priceInterval' => [
				'priceFrom' => $rental->getPriceFrom()->getSourceAmount(),
				'priceTo' => $rental->getPriceTo()->getSourceAmount()
			]
		];
		$form->setDefaults($defaults);
	}


	public function validateForm(BaseForm $form)
	{
		$values = $form->getFormattedValues();
		if($values['priceInterval']['priceFrom'] > $values['priceInterval']['priceTo']) {
			$form['priceInterval']['priceTo']->addError('Price from must be bigger than price to');
		}
	}


	public function processForm(BaseForm $form)
	{
		$values = $form->getFormattedValues();

		$rental = $this->rental;

		$currency = $this->em->getRepository(CURRENCY_ENTITY)->find($values['currency']);
		$rental->setCurrency($currency);

		$rental->setFloatPriceFrom($values['priceInterval']['priceFrom']);
		$rental->setFloatPriceTo($values['priceInterval']['priceTo']);

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

		$qb = $this->em->getRepository(RENTAL_PRICELIST_ROW_ENTITY)->createQueryBuilder('p');
		$qb->delete()
			->where('p.rental = ?1')->setParameter(1, $rental->id);

		$qb->getQuery()->execute();

		$this->em->flush();

		$this->onFormSuccess($form, $rental);
	}


}


interface IPricesFormFactory
{
	public function create(\Entity\Rental\Rental $rental);
}

<?php

namespace Extras\Forms\Container;

use AdminModule\Forms\Form;
use Doctrine\ORM\EntityManager;
use Entity\Currency;
use Entity\Rental\Rental;
use Environment\Collator;
use Nette\Forms\Container;
use Nette\Localization\ITranslator;
use Nette\DateTime;

class RentalPriceListContainer extends BaseContainer
{
	/**
	 * @var \Entity\Rental\Rental
	 */
	protected $rental;

	/**
	 * @var \Entity\Rental\PricelistRow
	 */
	protected $pricelistRows;

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	protected $em;

	/**
	 * @var \Nette\Localization\ITranslator
	 */
	protected $translator;

	/**
	 * @var \Entity\Currency
	 */
	protected $currency;
	protected $roomTypes;

	protected $date;
	protected $notes;

	protected $minPeople;
	protected $minNights;
	protected $bedCount = [];
	protected $extraBedCount = [];


	public function __construct(Currency $currency, EntityManager $em, Rental $rental = NULL, ITranslator $translator, Collator $collator)
	{
		parent::__construct();
		$this->em = $em;

		$this->rental = $rental;
		$this->translator = $translator;
		$this->currency = $currency;

		$rows = $em->getRepository(RENTAL_ROOM_TYPE_ENTITY)->findAll();
		$roomTypes = [];
		foreach($rows as $row) {
			$roomTypes[$row->id] = $translator->translate($row->name);
		}
		$collator->asort($roomTypes);
		$this->roomTypes = $roomTypes;

		$this->pricelistRows = $this->rental->getPricelistRows();

		$maxCount = 51;

		$this->extraBedCount[0] = "0 ".$translator->translate('o100000',0);
		for($i=1;$i<$maxCount;$i++) {
			$this->extraBedCount[$i] = "{$i} ".$translator->translate('o100000',$i);
			// $this->roomCount[$i] = "{$i}x";
			// $this->bedCount[$i] = "{$i} ".$translator->translate('o100006',$i);
		}

		$this->addDynamic('list', $this->containerBuilder, 1);
	}


	public function containerBuilder(Container $container)
	{
		$date = $container->addContainer('date');
		$today = (new DateTime)->modify('today');
		$dateFromControl = $date->addAdvancedDatePicker('from')
			->getControlPrototype()
			->setPlaceholder($this->translator->translate('o1043'));

		$dateFromControl->addCondition(\FrontModule\Forms\BaseForm::FILLED)
			->addRule(\FrontModule\Forms\BaseForm::RANGE, 'o100160', [$today, $today->modifyClone('+1 years')]);

		$dateToControl = $date->addAdvancedDatePicker('to')
			->getControlPrototype()
			->setPlaceholder($this->translator->translate('o1044'));

		$dateToControl->addCondition(\FrontModule\Forms\BaseForm::FILLED)
			->addRule(\FrontModule\Forms\BaseForm::RANGE, 'o100160', [$today, $today->modifyClone('+1 years')]);


		$container->addText('minPeople', '', $this->minPeople);
		$container->addText('minNights', '', $this->minNights);
		$container->addSelect('priceType', '', $this->roomTypes);
		// $container->addSelect('bedCount', '', $this->bedCount);
		// $container->addSelect('extraBedCount', '', $this->extraBedCount);
		$container->addText('notes', '', $this->notes);

		$container->addText('price', 'o100078')
			->setOption('append', $this->currency->getIso())
			->addRule(Form::RANGE, $this->translator->translate('o100105'), [0, 999999999999999]);

		$container->addHidden('entityId', '');
	}

	public function getFormattedValues($asArray = FALSE)
	{
		$values = $asArray
			? ['list'=>[]]
			: \Nette\ArrayHash::from(['list'=>[]]);

		$pricelistRowRepository = $this->em->getRepository(RENTAL_PRICELIST_ROW_ENTITY);
		$roomTypeRepository = $this->em->getRepository(RENTAL_ROOM_TYPE_ENTITY);

		foreach ($this->getComponents() as $control) {
			$list = $control->getValues();
			foreach($list as $key => $row) {
				if (!$row['price']) continue;

				$rowEntity = NULL;
				if (isset($row->entityId)) {
					$rowEntity = $pricelistRowRepository->find($row->entityId);
				}
				if (!$rowEntity) {
					$rowEntity = $pricelistRowRepository->createNew();
				}
				$rowEntity->rental = $this->rental;
				$rowEntity->roomCount = $row['roomCount'];
				$rowEntity->roomType = $roomTypeRepository->find($row['roomType']);
				$rowEntity->bedCount = $row['bedCount'];
				$rowEntity->extraBedCount = $row['extraBedCount'];
				$rowEntity->price = new \Extras\Types\Price($row['price'], $this->currency);

				$row['entity'] = $rowEntity;
				$values['list'][$key] = $row;
			}
		}
		return $values;
	}

	public function setDefaultsValues()
	{
		$priceLists = [];
		foreach($this->pricelistRows as $pricelistRow) {
			$priceLists[] = [
				// 'minPeople' => $pricelistRow->getMinPeople(),
				'extraBedCount' => $pricelistRow->getExtraBedCount(),
				'price' => $pricelistRow->getPrice()->getSourceAmount(),
				'entityId' => $pricelistRow->id
			];
		}

		$this->setDefaults(['list' => $priceLists]);
	}


	public function getMainControl()
	{
		return NULL;
	}


}

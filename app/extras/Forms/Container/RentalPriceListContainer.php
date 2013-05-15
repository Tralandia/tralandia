<?php

namespace Extras\Forms\Container;

use AdminModule\Forms\Form;
use Doctrine\ORM\EntityManager;
use Entity\Currency;
use Entity\Rental\Rental;
use Environment\Collator;
use Nette\Forms\Container;
use Nette\Localization\ITranslator;

class RentalPriceListContainer extends BaseContainer
{
	/**
	 * @var \Entity\Rental\Rental
	 */
	protected $rental;

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

	protected $roomCount = [];
	protected $bedCount = [];
	protected $extraBedCount = [];


	public function __construct(Currency $currency, EntityManager $em, Rental $rental = NULL, ITranslator $translator, Collator $collator)
	{
		parent::__construct();
		$this->em = $em;

		$this->rental = $rental;
		$this->translator = $translator;
		$this->currency = $currency;
		$this->roomTypes = $em->getRepository(RENTAL_ROOM_TYPE_ENTITY)->getForSelect($translator, $collator);

		$maxCount = 51;

		for($i=1;$i<$maxCount;$i++) {
			$this->roomCount[$i] = "{$i}x";
			$this->bedCount[$i] = "{$i} ".$translator->translate('o100006',$i);
			$this->extraBedCount[$i] = "{$i} ".$translator->translate('o100000',$i);
		}

		$this->addDynamic('list', $this->containerBuilder,2);
	}


	public function containerBuilder(Container $container)
	{
		$container->addSelect('roomCount', '', $this->roomCount);
		$container->addSelect('roomType', '', $this->roomTypes);
		$container->addSelect('bedCount', '', $this->bedCount);
		$container->addSelect('extraBedCount', '', $this->extraBedCount);

		$container->addText('price', 'o100078')
			->setOption('append', $this->currency->getIso() . ' ' . $this->translator->translate('o100004'))
			->addRule(Form::RANGE, $this->translator->translate('o100105'), [0, 999999999999999]);
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


	public function getMainControl()
	{
		return NULL;
	}


}

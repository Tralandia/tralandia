<?php

namespace Extras\Forms\Container;

use AdminModule\Forms\Form;
use Doctrine\ORM\EntityManager;
use Entity\Currency;
use Extras\Models\Repository\RepositoryAccessor;
use Environment\Collator;
use Nette\Forms\Container;
use Nette\Localization\ITranslator;

class RentalPriceListContainer extends BaseContainer
{

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	protected $em;

	/**
	 * @var \Nette\Localization\ITranslator
	 */
	protected $translator;

	/**
	 * @var \Extras\Models\Repository\RepositoryAccessor
	 */
	protected $rentalPricelistRowRepositoryAccessor;

	/**
	 * @var \Entity\Currency
	 */
	protected $currency;
	protected $roomTypes;

	protected $roomCount = [];
	protected $bedCount = [];
	protected $extraBedCount = [];


	public function __construct(Currency $currency, EntityManager $em, ITranslator $translator, Collator $collator, RepositoryAccessor $rentalPricelistRowRepositoryAccessor)
	{
		parent::__construct();
		$this->em = $em;

		$this->rentalPricelistRowRepositoryAccessor = $rentalPricelistRowRepositoryAccessor;
		$this->translator = $translator;
		$this->currency = $currency;
		$this->roomTypes = $em->getRepository(RENTAL_AMENITY_ENTITY)->findByRoomTypeTypeForSelect($translator);

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

	public function getValues($asArray = FALSE)
	{
		$values = $asArray ? array() : new \Nette\ArrayHash;
		foreach ($this->getComponents() as $control) {
			$list = $control->getValues();
			foreach($list as $values) {
				if ($values->entityId) {

				} else {
					$this->rentalPricelistRowRepositoryAccessor->get();
				}
			}
		}
		return $values;
	}


	public function getMainControl()
	{
		return NULL;
	}


}

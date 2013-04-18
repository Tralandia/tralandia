<?php

namespace Extras\Forms\Container;

use Doctrine\ORM\EntityManager;
use Environment\Collator;
use Nette\Localization\ITranslator;

class RentalPriceListContainer extends BaseContainer
{

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	protected $em;


	public function __construct(EntityManager $em, ITranslator $translator, Collator $collator)
	{
		parent::__construct();
		$this->em = $em;

		$roomTypes = $em->getRepository(RENTAL_AMENITY_ENTITY)->findByRoomTypeTypeForSelect($translator);
		$currencies = $em->getRepository(CURRENCY_ENTITY)->getForSelect($translator, $collator);


		$roomCount = [];
		$maxCount = 51;
		for($i=1;$i<$maxCount;$i++) {
			$extraBedCount[$i] = "{$i} ".$translator->translate('o100000',$i);
			$roomCount[$i] = "{$i}x";
			$bedCount[$i] = "{$i} ".$translator->translate('o100006',$i);
		}
		// $roomCount[$maxCount] = "{$maxCount}+";

		for($i=1;$i<4;$i++) {
			$rowContainer = $this->addContainer($i);
			$rowContainer->addSelect('roomCount', '', $roomCount);
			$rowContainer->addSelect('roomType', '', $roomTypes);
			$rowContainer->addSelect('bedCount', '', $bedCount);
			$rowContainer->addSelect('extraBedCount', '', $extraBedCount);
			$rowContainer->addPriceContainer('price', '', $currencies);
		}

		// $rowContainer = $this->addContainer();
		// $rowContainer->addHidden('jsonPriceList');

	}


	public function getMainControl()
	{
		return $this['1']['roomCount'];
	}


}

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
		$maxCount = 50;
		for($i=1;$i<$maxCount;$i++) {
			$roomCount[$i] = "{$i}x";
		}
		$roomCount[$maxCount] = "{$maxCount}+";

		for($i=1;$i<6;$i++) {
			$rowContainer = $this->addContainer($i);
			$rowContainer->addSelect('roomCount', '', $roomCount);
			$rowContainer->addSelect('roomType', '', $roomTypes);
			$rowContainer->addSelect('bedCount', '', $roomCount);
			$rowContainer->addSelect('extraBedCount', '', $roomCount);
			$rowContainer->addPriceContainer('price', '', $currencies);
		}

	}


	public function getMainControl()
	{
		return $this['1']['roomCount'];
	}


}

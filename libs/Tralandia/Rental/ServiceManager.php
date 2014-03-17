<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 30/01/14 07:34
 */

namespace Tralandia\Rental;


use Entity\Rental\Service;
use Nette;
use Tralandia\BaseDao;

class ServiceManager
{

	/**
	 * @var \Tralandia\BaseDao
	 */
	private $serviceDao;

	/**
	 * @var \Tralandia\BaseDao
	 */
	private $rentalDao;


	/**
	 * @param BaseDao $serviceDao
	 * @param BaseDao $rentalDao
	 */
	public function __construct(BaseDao $serviceDao, BaseDao $rentalDao)
	{
		$this->serviceDao = $serviceDao;
		$this->rentalDao = $rentalDao;
	}


	/**
	 * @param \Entity\Rental\Rental $rental
	 * @param $givenFor
	 *
	 * @throws \InvalidArgumentException
	 * @return \Entity\Rental\Service
	 */
	public function prolong(\Entity\Rental\Rental $rental, $givenFor)
	{
		if($givenFor == Service::GIVEN_FOR_SHARE) {
			$prolongBy = '+6 months';
		} else if($givenFor == Service::GIVEN_FOR_BACKLINK) {
			$prolongBy = '+12 months';
		} else {
			throw new \InvalidArgumentException();
		}

		$lastService = $rental->getLastService();

		if($lastService) $dateFrom = $lastService->getDateTo();
		else $dateFrom = new \DateTime();

		$dateFrom->modify('midnight');
		$dateTo = clone $dateFrom;
		$dateTo->modify($prolongBy);

		/** @var $newService Service */
		$newService = $this->serviceDao->createNew();
		$newService->setGivenFor($givenFor)
				->setServiceType(Service::TYPE_FEATURED)
				->setDateFrom($dateFrom)
				->setDateTo($dateTo);

		$rental->addService($newService);
		$this->rentalDao->save($rental, $newService);

		return $newService;
	}


}

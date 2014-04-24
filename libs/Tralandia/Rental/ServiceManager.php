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
	 * @param string $serviceType
	 *
	 * @throws \InvalidArgumentException
	 * @return \Entity\Rental\Service
	 */
	public function prolong(\Entity\Rental\Rental $rental, $givenFor, $serviceType = Service::TYPE_FEATURED)
	{
		if($givenFor == Service::GIVEN_FOR_SHARE) {
			$prolongBy = '+6 months';
		} else if($givenFor == Service::GIVEN_FOR_BACKLINK) {
			$prolongBy = '+12 months';
		} else if($givenFor == Service::GIVEN_FOR_MEMBERSHIP && $serviceType == Service::TYPE_PREMIUM_PS) {
			$prolongBy = '+100 years';
		} else {
			throw new \InvalidArgumentException();
		}

		$lastService = $rental->getLastService($serviceType);

		if($lastService) $dateFrom = $lastService->getDateTo();
		else $dateFrom = new \DateTime();

		$dateFrom->modify('midnight');
		$dateTo = clone $dateFrom;
		$dateTo->modify($prolongBy);

		/** @var $newService Service */
		$newService = $this->serviceDao->createNew();
		$newService->setGivenFor($givenFor)
				->setServiceType($serviceType)
				->setDateFrom($dateFrom)
				->setDateTo($dateTo);

		$rental->addService($newService);
		$this->rentalDao->save($rental, $newService);

		return $newService;
	}


	public function hasPremiumPS($rental)
	{
		$qb = $this->serviceDao->createQueryBuilder('s');
		$qb->andWhere('s.serviceType = ?1')
			->andWhere('s.rental = ?2')
			->andWhere('s.dateFrom <= ?3')
			->andWhere('s.dateTo >= ?3')
			->setParameters([1 => Service::TYPE_PREMIUM_PS, 2 => $rental->id, 3 => new \DateTime()]);

		$result = $qb->getQuery()->getOneOrNullResult();

		return (bool) $result;
	}


}

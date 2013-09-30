<?php
/**
 * This file is part of the Tralandia.
 * User: david
 * Created at: 9/25/13 2:49 PM
 */

namespace Tralandia\Rental;


use Entity\Location\Location;
use Nette;
use Tralandia\BaseDao;

class Rentals {


	/**
	 * @var \Tralandia\BaseDao
	 */
	private $locationsDao;

	/**
	 * @var \Tralandia\BaseDao
	 */
	private $rentalDao;


	/**
	 * @param \Tralandia\BaseDao $rentalDao
	 * @param BaseDao $locationsDao
	 */
	public function __construct(BaseDao $rentalDao, BaseDao $locationsDao)
	{
		$this->locationsDao = $locationsDao;
		$this->rentalDao = $rentalDao;
	}


	/**
	 * @return int
	 */
	public function worldwideCount()
	{
		$qb = $this->locationsDao->createQueryBuilder('l');

		$qb->select('sum(l.rentalCount) as total');

		return $qb->getQuery()->getSingleScalarResult();
	}


	/**
	 * @param Location $primaryLocation
	 * @param null $live
	 * @param \DateTime $dateFrom
	 * @param \DateTime $dateTo
	 *
	 * @return array
	 */
	public function getCounts(Location $primaryLocation = NULL, $live = NULL, \DateTime $dateFrom = NULL, \DateTime $dateTo = NULL) {
		$qb = $this->rentalDao->createQueryBuilder('r');

		$qb->select('l.id locationId', 'COUNT(r.id) as c')
			->innerJoin('r.address', 'a')
			->innerJoin('a.primaryLocation', 'l');

		if ($primaryLocation) {
			$qb->where($qb->expr()->eq('a.primaryLocation', $primaryLocation->id));
		} else {
			$qb->groupBy('a.primaryLocation');
		}

		if ($live) {
			$qb->andWhere($qb->expr()->eq('r.status', \Entity\Rental\Rental::STATUS_LIVE));
		}

		if ($dateFrom && $dateTo) {
			$qb->andWhere($qb->expr()->gte('r.created', '?1'));
			$qb->andWhere($qb->expr()->lt('r.created', '?2'));
			$qb->setParameter(1, $dateFrom, \Doctrine\DBAL\Types\Type::DATETIME);
			$qb->setParameter(2, $dateTo, \Doctrine\DBAL\Types\Type::DATETIME);
		}

		$result = $qb->getQuery()->getResult();
		$myResult = array();
		foreach ($result as $key => $value) {
			$myResult[$value['locationId']] = $value['c'];
		}
		return $myResult;
	}


	/**
	 * @param \Entity\Rental\Rental $rental
	 *
	 * @return bool
	 */
	public function isFeatured(\Entity\Rental\Rental $rental) {
		$qb = $this->rentalDao->createQueryBuilder('r');

		$qb->join('r.services', 's')
			->where($qb->expr()->eq('r.id', $rental->id))
			->andWhere($qb->expr()->eq('r.status', \Entity\Rental\Rental::STATUS_LIVE))
			->andWhere($qb->expr()->eq('s.serviceType', '?1'))
			->andWhere($qb->expr()->lte('s.dateFrom', '?2'))
			->andWhere($qb->expr()->gt('s.dateTo', '?2'))
			->setParameter(1, 'featured')
			->setParameter(2, new \Nette\DateTime(), \Doctrine\DBAL\Types\Type::DATETIME)
		;
		return (bool) $qb->getQuery()->getOneOrNullResult();
	}


	/**
	 * @param int $limit
	 *
	 * @return \Entity\Rental\Rental[]
	 */
	public function getFeaturedRentals($limit=99)
	{
		$qb = $this->rentalDao->createQueryBuilder('r');

		$qb->innerJoin('r.services', 's')
			->andWhere($qb->expr()->eq('r.status', \Entity\Rental\Rental::STATUS_LIVE))
			->andWhere($qb->expr()->eq('s.serviceType', '?1'))
			->andWhere($qb->expr()->lte('s.dateFrom', '?2'))
			->andWhere($qb->expr()->gt('s.dateTo', '?2'))
			->setParameter(1, 'featured')
			->setParameter(2, new \Nette\DateTime(), \Doctrine\DBAL\Types\Type::DATETIME)
			->orderBy('r.rank', 'DESC')
			->setMaxResults($limit)
		;

		return $qb->getQuery()->getResult();
	}


}

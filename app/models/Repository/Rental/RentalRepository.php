<?php
namespace Repository\Rental;

use Doctrine\ORM\Query\Expr;

/**
 * RentalRepository class
 *
 * @author Dávid Ďurika
 */
class RentalRepository extends \Repository\BaseRepository {

	public function findFeatured(\Entity\Location\Location $location) {
		$qb = $this->_em->createQueryBuilder();

		$qb->select('r.id')
			->from($this->_entityName, 'r')
			->join('r.services', 's')
			->where($qb->expr()->eq('r.primaryLocation', $location->id))
			->andWhere($qb->expr()->eq('r.status', \Entity\Rental\Rental::STATUS_LIVE))
			->andWhere($qb->expr()->eq('s.serviceType', '?1'))
			->andWhere($qb->expr()->lte('s.dateFrom', '?2'))
			->andWhere($qb->expr()->gt('s.dateTo', '?2'))
			->setParameter(1, 'featured')
			->setParameter(2, new \Nette\DateTime(), \Doctrine\DBAL\Types\Type::DATETIME)
			;

		return $qb->getQuery()->getResult();
	}

	public function isFeatured(\Entity\Rental\Rental $rental) {
		$qb = $this->_em->createQueryBuilder();

		$qb->select('r.id')
			->from($this->_entityName, 'r')
			->join('r.services', 's')
			->where($qb->expr()->eq('r.id', $rental->id))
			->andWhere($qb->expr()->eq('r.status', \Entity\Rental\Rental::STATUS_LIVE))
			->andWhere($qb->expr()->eq('s.serviceType', '?1'))
			->andWhere($qb->expr()->lte('s.dateFrom', '?2'))
			->andWhere($qb->expr()->gt('s.dateTo', '?2'))
			->setParameter(1, 'featured')
			->setParameter(2, new \Nette\DateTime(), \Doctrine\DBAL\Types\Type::DATETIME)
			;
		return $qb->getQuery()->getOneOrNullResult();
	}

	public function getCounts(\Entity\Location\Location $primaryLocation = NULL, $live = FALSE, \Nette\DateTime $dateFrom = NULL, \Nette\DateTime $dateTo = NULL) {
		$qb = $this->_em->createQueryBuilder();

		$qb->select('l.id locationId', 'COUNT(r.id) as c')
			->from($this->_entityName, 'r')
			->join('r.primaryLocation', 'l');
		if ($primaryLocation) {
			$qb->where($qb->expr()->eq('r.primaryLocation', $location->id));
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
		if (!$primaryLocation) {
			$qb->groupBy('r.primaryLocation');
		}

		$result = $qb->getQuery()->getResult();
		$myResult = array();
		foreach ($result as $key => $value) {
			$myResult[$value['locationId']] = $value['c'];
		}
		return $myResult;
	}

}
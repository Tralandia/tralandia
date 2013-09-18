<?php
namespace Repository\Rental;

use Doctrine\ORM\Query\Expr;
use Entity\Location\Location;
use Entity\Rental\Rental;

/**
 * RentalRepository class
 *
 * @author Dávid Ďurika
 */
class RentalRepository extends \Repository\BaseRepository {

	public function findFeatured(Location $location) {
		$qb = $this->_em->createQueryBuilder();

		$qb->select('r.id')
			->from($this->_entityName, 'r')
			->join('r.address', 'a')
			->join('r.services', 's')
			->where($qb->expr()->eq('a.primaryLocation', $location->id))
			->andWhere($qb->expr()->eq('r.status', \Entity\Rental\Rental::STATUS_LIVE))
			->andWhere($qb->expr()->eq('s.serviceType', '?1'))
			->andWhere($qb->expr()->lte('s.dateFrom', '?2'))
			->andWhere($qb->expr()->gt('s.dateTo', '?2'))
			->setParameter(1, 'featured')
			->setParameter(2, new \Nette\DateTime(), \Doctrine\DBAL\Types\Type::DATETIME)
			;

		return $qb->getQuery()->getResult();
	}


	public function findByEmailOrUserEmail($email)
	{
		$qb = $this->_em->createQueryBuilder();

		$qb->select('r')
			->from($this->_entityName, 'r')
			->join('r.user', 'u')
			->where($qb->expr()->eq('r.email', ':email'))
			->orWhere($qb->expr()->eq('u.login', ':email'))
			->setParameter('email', $email);

		return $qb->getQuery()->getResult();
	}


	/**
	 * @param Location $location
	 * @param null $status
	 * @param array $order
	 *
	 * @return \Doctrine\ORM\QueryBuilder
	 */
	public function findByPrimaryLocationQB(Location $location, $status = NULL, array $order = NULL)
	{
		$qb = $this->createQueryBuilder('r');

		$qb->innerJoin('r.address', 'a')
			->andWhere($qb->expr()->eq('a.primaryLocation', $location->getId()));

		if ($status != NULL) {
			$qb->andWhere($qb->expr()->eq('r.status', $status ? Rental::STATUS_LIVE : Rental::STATUS_DRAFT));
		}

		if($order) {
			foreach($order as $key => $value) {
				$qb->addOrderBy($key, $value);
			}
		}

		return $qb;
	}


	public function findByPrimaryLocation(Location $location, $status = NULL, array $order = NULL)
	{
		$qb = $this->findByPrimaryLocationQB($location, $status, $order);

		return $qb->getQuery()->getResult();
	}

	/**
	 * @param string $search
	 * @param Location $primaryLocation
	 * @return array
	 */
	public function findSuggestForSearch($search, Location $primaryLocation)
	{
		$qb = $this->_em->createQueryBuilder();

		$qb->select('r.id, t.translation as name')
			->from($this->_entityName, 'r')
			->join('r.address', 'a')
			->join('r.name', 'n')
			->join('n.translations', 't')
			->andWhere($qb->expr()->eq('a.primaryLocation', $primaryLocation->getId()))
			->andWhere($qb->expr()->like('t.translation', '?1'))->setParameter(1, "%$search%")
			->setMaxResults(20);

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

	public function getCounts(Location $primaryLocation = NULL, $live = NULL, \DateTime $dateFrom = NULL, \DateTime $dateTo = NULL) {
		$qb = $this->_em->createQueryBuilder();

		$qb->select('l.id locationId', 'COUNT(r.id) as c')
			->from($this->_entityName, 'r')
			->join('r.address', 'a')
			->join('a.primaryLocation', 'l');
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

	public function getFeaturedRentals($limit=99)
	{
		$qb = $this->_em->createQueryBuilder();

		$qb->select('r')
			->from($this->_entityName, 'r')
			->innerJoin('r.services', 's')
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

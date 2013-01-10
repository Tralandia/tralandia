<?php
namespace Repository\Location;

use Doctrine\ORM\Query\Expr;

/**
 * LocationRepository class
 */
class LocationRepository extends \Repository\BaseRepository {

	public function getItems() {
		$query = $this->_em->createQueryBuilder();
		$query->select('e')->from($this->_entityName, 'e')->setMaxResults(60);
		return $query->getQuery()->getResult();
	}

	public function getRentalCounts() {
		$qb = $this->_em->createQueryBuilder();

		$qb->select('l.id, count(r.id) as c')
			->from($this->_entityName, 'l')
			->join('l.primaryRentals', 'r')
			->where($qb->expr()->eq('r.status', \Entity\Rental\Rental::STATUS_LIVE))
			->addGroupBy('l.id')
    		->having('c > 0')
		;

		return $qb->getQuery()->getResult();
	}

	public function getCountriesForSelect()
	{
		$qb = $this->_em->createQueryBuilder();

		$qb->select('l.id, n.id AS name')
			->from($this->_entityName, 'l')
			->join('l.type', 't')
			->join('l.name', 'n')
			->where($qb->expr()->eq('t.slug', ':country'))
			->setParameter('country', 'country');

		$return = [];
		$rows = $qb->getQuery()->getResult();
		foreach($rows as $row) {
			$return[$row['id']] = $row['name'];
		}

		return $return;
	}

	public function getCountriesPhonePrefixes() {

		$qb = $this->_em->createQueryBuilder();

		$qb->select('l.id, l.iso, l.phonePrefix')
			->from($this->_entityName, 'l')
			->join('l.type', 't')
			->where($qb->expr()->eq('t.slug', ':country'))
			->setParameter('country', 'country');

		$return = [];
		$rows = $qb->getQuery()->getResult();
		foreach($rows as $row) {
			$return[$row['iso']] = strtoupper($row['iso']) . ' (+'.$row['phonePrefix'].')';
		}

		return $return;

	}
}
<?php
namespace Repository\Location;

use Doctrine\ORM\Query\Expr;

/**
 * LocationRepository class
 */
class LocationRepository extends \Repository\BaseRepository {

	/**
	 * DataSource pre grid
	 * @return Doctrine\ORM\QueryBuilder
	 */
	public function getDefaultDataSource($type) {
		return $this->_em->createQueryBuilder()
			->select('e')
			->from($this->_entityName, 'e')
			->leftJoin('e.type', 't')
			->where('t.slug = :slug')->setParameter('slug', $type)
			->groupBy('e.id');
	}

	public function getItems() {
		$query = $this->_em->createQueryBuilder();
		$query->select('e')->from($this->_entityName, 'e')->setMaxResults(60);
		return $query->getQuery()->getResult();
	}

	public function getWorldwideRentalCount() {
		$qb = $this->_em->createQueryBuilder();

		$qb->select('sum(l.rentalCount) as total')
			->from($this->_entityName, 'l');

		return $qb->getQuery()->getResult()[0]['total'];
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
			->setParameter('country', 'country')
			->groupBy('l.phonePrefix')
			->orderBy('l.iso')
			;

		$return = [];
		$rows = $qb->getQuery()->getResult();
		$continentType = $this->related('type')->findOneBySlug('continent');
		$usa = $this->findOneBy(array('slug' => 'usa', 'type' => $continentType));
		$australia = $this->findOneBy(array('slug' => 'australia', 'type' => $continentType));

		foreach($rows as $row) {
			if ($row['phonePrefix'] == 1) {
				$return[$row['phonePrefix']] = strtoupper($usa->iso) . ' (+'.$row['phonePrefix'].')';
			} else if ($row['phonePrefix'] == 61) {
				$return[$row['phonePrefix']] = strtoupper($australia->iso) . ' (+'.$row['phonePrefix'].')';
			} else {
				$return[$row['phonePrefix']] = strtoupper($row['iso']) . ' (+'.$row['phonePrefix'].')';
			}
		}

		return $return;

	}
}
<?php
namespace Repository;

use Doctrine\ORM\Query\Expr;

/**
 * LocationRepository class
 */
class LocationRepository extends BaseRepository {

	public function getItems() {
		$query = $this->_em->createQueryBuilder();
		$query->select('e')->from($this->_entityName, 'e')->setMaxResults(20);
		return $query->getQuery()->getResult();
	}
}
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
}
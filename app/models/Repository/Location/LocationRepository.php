<?php
namespace Repository\Location;

use Doctrine\ORM\Query\Expr;

/**
 * LocationRepository class
 */
class LocationRepository extends \Repository\BaseRepository {

	public function getItems() {
		$query = $this->_em->createQueryBuilder();
		$query->select('e, p')->from($this->_entityName, 'e')->leftJoin('e.name', 'p');
		return $query->getQuery()->getResult();
	}
}
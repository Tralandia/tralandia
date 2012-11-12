<?php
namespace Repository;

use Doctrine\ORM\Query\Expr;

/**
 * LocationRepository class
 */
class LocationRepository extends BaseRepository {

	public function getItems() {
		$query = $this->_em->createQueryBuilder();
		$query->select('e')->from($this->_entityName, 'e');
		return $query->getQuery()->getResult();

		return array(
			(object)array('id' => 1, 'name' => 'Položka 1'),
			(object)array('id' => 2, 'name' => 'Položka 2'),
			(object)array('id' => 3, 'name' => 'Položka 3'),
			(object)array('id' => 4, 'name' => 'Položka 4'),
			(object)array('id' => 5, 'name' => 'Položka 5'),
			(object)array('id' => 6, 'name' => 'Položka 6'),
			(object)array('id' => 7, 'name' => 'Položka 7'),
			(object)array('id' => 8, 'name' => 'Položka 8'),
			(object)array('id' => 9, 'name' => 'Položka 9'),
			(object)array('id' => 10, 'name' => 'Položka 10'),
			(object)array('id' => 11, 'name' => 'Položka 11'),
			(object)array('id' => 12, 'name' => 'Položka 12'),
			(object)array('id' => 52, 'name' => 'Položka 52'),
			(object)array('id' => 53, 'name' => 'Položka 53'),
			(object)array('id' => 54, 'name' => 'Položka 54')
		);
	}
}
<?php

class RentalRepository extends \BaseRepository {

	public function getDataSource() {
		$query = $this->_em->createQueryBuilder();
		return $query->select('r, u, c, 1 b')
			->from('Rental', 'r')
			->leftJoin('r.user', 'u')
			->leftJoin('r.country', 'c');
	}


}

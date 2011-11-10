<?php

class UserRepository extends \BaseRepository {

	public function getDataSource() {
		$query = $this->_em->createQueryBuilder();
		return $query->select('u, c')
			->from('User', 'u')
			->leftJoin('u.country', 'c');
	}

}

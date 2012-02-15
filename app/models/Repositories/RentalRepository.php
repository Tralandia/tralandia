<?php

class RentalRepository extends \BaseRepository {

	public function getDataSource() {
		$query = $this->_em->createQueryBuilder();
		//$query->useResultCache(true);
		//$query->setResultCacheDriver(new \Doctrine\Common\Cache\ApcCache());
		
		//$query->useResultCache(true, 3600, 'my_custom_id');
		
		$query = $query->select('r, u, c, 1 b')
			->from('Rental', 'r')
			->leftJoin('r.user', 'u')
			->leftJoin('r.country', 'c');
			
		$query->useResultCache(true);
		return $query;
	}


}

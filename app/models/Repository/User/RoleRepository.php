<?php

namespace Repository;

/**
 * RoleRepository class
 *
 * @author Dávid Ďurika
 */
class RoleRepository extends BaseRepository {

	public function forAcl() {
		return $this->fetchPairs('id', 'slug');
	}

}
<?php
namespace Repository\User;

/**
 * RoleRepository class
 *
 * @author Dávid Ďurika
 */
class RoleRepository extends \Repository\BaseRepository {

	public function forAcl() {
		return $this->getPairs('id', 'slug');
	}
}
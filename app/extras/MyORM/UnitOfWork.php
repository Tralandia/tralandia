<?php

namespace MyORM;

use Doctrine\ORM;

class UnitOfWork {


	public function getEntityPersister($entityName) {
		
	}
	
	public function tryGetById($id, $rootClassName) {
		$idHash = implode(' ', (array) $id);
		if (isset($this->identityMap[$rootClassName][$idHash])) {
			return $this->identityMap[$rootClassName][$idHash];
		}
		return false;
    }
}
<?php

namespace Tra\Services;

class BaseService extends Service {
	
	public function getList($class, $value) {
		return $this->em->getRepository($class)->fetchPairs($class::PRIMARY_KEY, $value);
	}
}

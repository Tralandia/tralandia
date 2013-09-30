<?php

namespace Service;

use Extras;

/**
 * Rodic sluzieb
 * @author Branislav Vaculčiak
 */
class BaseService extends Extras\Models\Service\Service {

	/**
	 * @return int
	 */
	public function getId() {
		return $this->entity->getId();
	}

	/**
	 * @return \DateTime
	 */
	public function getCreated() {
		return $this->entity->getCreated();
	}

	/**
	 * @return \DateTime
	 */
	public function getUpdated() {
		return $this->entity->getUpdated();
	}
}

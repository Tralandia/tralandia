<?php

namespace Service;

use Extras;

/**
 * Rodic sluzieb
 * @author Branislav Vaculčiak
 */
class Base extends Extras\Service\Service {

	/**
	 * @return int
	 */
	public function getId() {
		return $this->entity->getId();
	}

	/**
	 * @return Nette\DateTime
	 */
	public function getCreated() {
		return $this->entity->getCreated();
	}

	/**
	 * @return Nette\DateTime
	 */
	public function getUpdated() {
		return $this->entity->getUpdated();
	}
}
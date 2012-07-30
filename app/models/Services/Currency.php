<?php

namespace Services;

class Currency extends Base {

/*
	public function __constructor(Doctrine\ORM\EntityManager $entityManager, Entity\BaseEntity $entity) {
		parent::__construct($entityManager, $entity);
	}
*/

	/**
	 * Ulozenie clanku
	 * @return Article
	 */
	public function save() {
		debug($this);
		//$this->repository->save($this->entity);
	}
}
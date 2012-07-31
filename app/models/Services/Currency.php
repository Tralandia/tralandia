<?php

namespace Services;

use Doctrine, Entity;

/**
 * Sluzba meny
 * @author Branislav Vaculčiak
 */
class Currency extends Base {

	/**
	 * @param Doctrine\ORM\EntityManager
	 * @param IEntity
	 */
	public function __construct(Doctrine\ORM\EntityManager $entityManager, Entity\Currency $entity) {
		parent::__construct($entityManager, $entity);
	}

	/**
	 * Ukazkovy proces
	 */
	public function process($a, $b) {
		return $a + $b;
	}

	/**
	 * Ulozenie meny
	 */
	public function save() {
		$this->getEntityManager()->persist($this->entity);
		$this->getEntityManager()->flush();
		return true; // TODO: stale vracia true
	}

	/**
	 * Vymazanie meny
	 */
	public function delete() {
		$this->getEntityManager()->remove($this->entity);
		$this->getEntityManager()->flush();
		return true; // TODO: stale vracia true
	}
}
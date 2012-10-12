<?php

namespace Service;

use Doctrine, Entity;

/**
 * Sluzba meny
 * @author Branislav Vaculčiak
 */
class CurrencyService extends Base {

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
}
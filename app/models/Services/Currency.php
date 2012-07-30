<?php

namespace Services;

/**
 * Sluzba meny
 * @author Branislav Vaculčiak
 */
class Currency extends Base {

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
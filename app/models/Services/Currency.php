<?php

namespace Services;

/**
 * Sluzba meny
 * @author Branislav Vaculčiak
 */
class Currency extends Base {

	/**
	 * Ulozenie meny
	 */
	public function save() {
		$this->getEntityManager()->persist($this->entity);
		$this->getEntityManager()->flush();
	}

	/**
	 * Vymazanie meny
	 */
	public function delete() {
		$this->getEntityManager()->remove($this->entity);
		$this->getEntityManager()->flush();
	}
}
<?php

namespace Extras\Models;

use Doctrine\ORM\EntityManager;

/**
 * 
 */
class ServiceList extends \Nette\ArrayList {

	/**
	 * @var EntityManager
	 */
	private static $em = null;

	/**
	 * Nastavenie entity manazera
	 * @param EntityManager
	 */
	public static function setEntityManager(EntityManager $em) {
		self::$em = $em;
	}

	/**
	 * Ziskanie entity manazera
	 * @return EntityManager
	 */
	protected function getEntityManager() {
		return self::$em;
	}

	/**
	 * Alias na entity manazera
	 * @return EntityManager
	 */
	protected function getEm() {
		return self::getEntityManager();
	}

	public function getIterator() {
		debug($this->getEm());
		return parent::getIterator();
	}


}
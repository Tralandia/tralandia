<?php

namespace Extras\Models;

use Nette\Object,
	Nette\OutOfRangeException,
	Doctrine\ORM\EntityManager;

/**
 * Abstrakcia zoznamu
 */
abstract class ServiceList extends Object implements \ArrayAccess, \Countable, \IteratorAggregate, IServiceList {


	const RETIRN_ENTITIES = 1;
	const RETURN_SERVICES = 2;

	/**
	 * @var array
	 */
	protected $list = null;

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

	/**
	 * Vracia iterator nad vsetkymi polozkami
	 * @return \ArrayIterator
	 */
	public function getIterator() {
		if ($this->list === null) {
			$this->list = array();
			$this->prepareList();
		}
		
		return new \ArrayIterator($this->list);
	}

	/**
	 * Vracia pocet poloziek
	 * @return int
	 */
	public function count() {
		return count($this->list);
	}

	/**
	 * Setter polozky
	 * @param  int
	 * @param  mixed
	 * @return void
	 * @throws OutOfRangeException
	 */
	public function offsetSet($index, $value) {
		if ($index === NULL) {
			$this->list[] = $value;

		} elseif ($index < 0 || $index >= count($this->list)) {
			throw new OutOfRangeException("Offset invalid or out of range");

		} else {
			$this->list[(int) $index] = $value;
		}
	}

	/**
	 * Getter polozky
	 * @param  int
	 * @return mixed
	 * @throws OutOfRangeException
	 */
	public function offsetGet($index) {
		if ($index < 0 || $index >= count($this->list)) {
			throw new OutOfRangeException("Offset invalid or out of range");
		}
		return $this->list[(int) $index];
	}

	/**
	 * Zistenie ci polozka existuje
	 * @param  int
	 * @return bool
	 */
	public function offsetExists($index) {
		return $index >= 0 && $index < count($this->list);
	}

	/**
	 * Zrusenie polozky
	 * @param  int
	 * @return void
	 * @throws OutOfRangeException
	 */
	public function offsetUnset($index) {
		if ($index < 0 || $index >= count($this->list)) {
			throw new OutOfRangeException("Offset invalid or out of range");
		}
		array_splice($this->list, (int) $index, 1);
	}
}
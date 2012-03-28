<?php

namespace Extras\Models;

use Nette\Object,
	Nette\OutOfRangeException,
	Doctrine\ORM\EntityManager;

/**
 * Abstrakcia zoznamu
 */
abstract class ServiceList extends Object implements \ArrayAccess, \Countable, \Iterator, IServiceList {

	const RETURN_ENTITIES = 1;

	/**
	 * @var array
	 */
	protected $list = NULL;

	protected $iteratorPosition = 0;

	protected $returnAs = self::RETURN_ENTITIES;

	/**
	 * @var EntityManager
	 */
	private static $em = NULL;


	public function __construct() {
		$this->iteratorPosition = 0;
	}

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


	public function returnAs($returnAs) {
		$this->returnAs = $returnAs;

		return $this;
	}

	// /**
	//  * Vracia iterator nad vsetkymi polozkami
	//  * @return \ArrayIterator
	//  */
	// public function getIterator() {
	// 	debug('getIterator');
	// 	if ($this->list === null) {
	// 		$this->list = array();
	// 		$this->prepareList();
	// 	}
		
	// 	return new \ArrayIterator($this->list);
	// }

	public function getIteratorAsServices($serviceName) {
		$iterator = $this->getIterator();
		$newIterator = array();
		foreach ($iterator as $key => $val) {
			if($val instanceof Entity) {
				$newIterator[] = $serviceName::get($val);
			} else if($val instanceof $serviceName) {
				$newIterator[] = $val;
			} else {
				// @todo method or operation is not implemented
				throw new \Nette\NotImplementedException('Requested method or operation is not implemented');
			}
		}
		return $newIterator;
	}

	/* --------------------- Inherited methods from Countable --------------------- */
	/**
	 * Vracia pocet poloziek
	 * @return int
	 */
	public function count() {
		return count($this->list);
	}

	/* --------------------- Inherited methods from Iterator --------------------- */


	function rewind() {
		$this->iteratorPosition = 0;
	}

	function current() {
		return $this->offsetGet($this->iteratorPosition);
	}

	function key() {
		return $this->iteratorPosition;
	}

	function next() {
		++$this->iteratorPosition;
	}

	function valid() {
		return isset($this->list[$this->iteratorPosition]);
	}



	/* --------------------- Inherited methods from ArrayAccess --------------------- */
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

		debug($index);
		$value = $this->list[$index];
		if($this->returnAs != self::RETURN_ENTITIES) {
			if($value instanceof Entity) {
				$serviceName = $this->returnAs;
				$value = $serviceName::get($value);
			} else if($value instanceof $this->returnAs) {
			} else {
				// @todo method or operation is not implemented
				throw new \Nette\NotImplementedException('Requested method or operation is not implemented');
			}
		}

		return $value;
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
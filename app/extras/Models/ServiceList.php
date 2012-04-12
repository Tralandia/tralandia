<?php

namespace Extras\Models;

use Nette\Object,
	Nette\OutOfRangeException,
	Tra\Utils\Strings,
	Doctrine\ORM\EntityManager;

/**
 * Abstrakcia zoznamu
 */
abstract class ServiceList extends Object implements \ArrayAccess, \Countable, \Iterator {

	const RETURN_ENTITIES = 'Entities';

	/**
	 * @var string
	 */
	const MAIN_ENTITY_NAME = null;


	protected $iteratorPosition = 0;

	protected $returnAs = self::RETURN_ENTITIES;

	/**
	 * @var EntityManager
	 */
	private static $em = NULL;

	/**
	 * @var array
	 */
	private $list = array();


	public function __construct($param = NULL) {
		if(is_array($param)) {
			$this->setList($param);
		} else if(is_string($param)) {
			$this->prepareBaseList($param);
		} else if($param === NULL) {
			// create empty list
		} else {
			throw new \Nette\InvalidArgumentException('Argument does not match with the expected value');
		}
	}

	public static function __callStatic($name, $arguments) {
		list($nameTemp, $nameBy, $nameIn) = Strings::match($name, '~^getBy([A-Za-z]+)In([A-Za-z]+)$~');
		if($nameTemp && $nameBy && $nameIn) {
			$nameBy = Strings::firstLower($nameBy);
			$nameIn = Strings::firstLower($nameIn);
			return static::getByIn($nameBy, $nameIn, array_shift($arguments), array_shift($arguments), array_shift($arguments), array_shift($arguments), array_shift($arguments), array_shift($arguments));
		} else if(Strings::startsWith($name, 'getBy')) {
			$name = Strings::firstLower(substr($name, 5));
			$by = array_shift($arguments);
			$orderBy = array_shift($arguments);
			$limit = array_shift($arguments);
			$offset = array_shift($arguments);
			$entityName = array_shift($arguments);
			return static::getBy(array($name => $by), $orderBy, $limit, $offset, $entityName);
		} else {
			return parent::__callStatic($name, $arguments);
		}
	}

	public static function prepareCriteria($criteria) {
		foreach ($criteria as $key => $value) {
			if($value instanceof Service || $value instanceof Entity) {
				$criteria[$key] = $value->id;
			} else if(is_array($value)) {
				$criteria[$key] = static::prepareCriteria($value);
			}
		}

		return $criteria;
	}

	/**
	 * criteria array('field' => 'value')
	 * orderBy array('field' => 'ASC')
	 */
	public static function getBy(array $criteria, array $orderBy = NULL, $limit = NULL, $offset = NULL, $entityName = NULL) {
		$criteria = static::prepareCriteria($criteria);

		if($entityName === NULL) {
			$entityName = static::getMainEntityName();
		}

		$serviceList = new static;
		$repository = $serviceList->getEm()->getRepository($entityName);
		$serviceList->setList($repository->findBy($criteria, $orderBy, $limit, $offset));
		return $serviceList;
	}

	public static function getAll(array $orderBy = NULL, $limit = NULL, $offset = NULL, $entityName = NULL) {
		if($entityName === NULL) {
			$entityName = static::getMainEntityName();
		}

		$serviceList = new static;
		$repository = $serviceList->getEm()->getRepository($entityName);
		$serviceList->setList($repository->findBy(array(), $orderBy, $limit, $offset));
		return $serviceList;

	}


	public static function getByIn($nameBy, $nameIn, $by, array $in, array $orderBy = NULL, $limit = NULL, $offset = NULL, $entityName = NULL) {
		if($entityName === NULL) {
			$entityName = static::getMainEntityName();
		}

		$parsedIn = array();
		foreach ($in as $key => $value) {
			if($value instanceof Service || $value instanceof Entity) {
				$parsedIn[] = $value->id;
			} else {
				$parsedIn[] = $value;
			}
		}

		$serviceList = new static;

		$qb = $serviceList->getEm()->createQueryBuilder();
		$qb->select('e')
			->from($entityName, 'e')
			->where('e.'.$nameBy.' = :by')
			->andWhere($qb->expr()->in('e.'.$nameIn, $parsedIn))
			->setParameter('by', $by);
		if($orderBy) {
			foreach ($orderBy as $key => $value) {
				$qb->addOrderBy('e.'.$key, $value);
			}
		}
		if($limit) $qb->setMaxResults($limit);
		if($offset) $qb->setFirstResult($offset);
		$serviceList->setList($qb->getQuery()->getResult());

		return $serviceList;

	}



	protected function prepareBaseList($entity) {
		$query = $this->getEm()->createQueryBuilder();
		$query->select('e')->from($entity, 'e');
		$this->setList($query->getQuery()->getResult());
	}

	public function setList($list) {
		$this->list = $list;
		$this->iteratorPosition = 0;
	}

	/**
	 * Ziskanie nazvu hlavnej entity
	 * @return string
	 */
	public static function getMainEntityName() {
		if (!static::MAIN_ENTITY_NAME) {
			throw new \Exception("Este nebola zadana `mainEntity`, preto nemozem ziskat jej nazov");
		}
		
		return static::MAIN_ENTITY_NAME;
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
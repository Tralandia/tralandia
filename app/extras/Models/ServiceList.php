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

	/**
	 * @var \Doctrine\ORM\QueryBuilder
	 */
	private $dataSource;


	protected function __construct($param = NULL) {
		if(is_array($param)) {
			$this->setList($param);
		} else if($param === NULL) {
			// create empty list
		} else {
			throw new \Nette\InvalidArgumentException('Argument does not match with the expected value');
		}
	}

	public function getDataSource() {
		return $this->dataSource;
	}

	protected function setDataSource(\Doctrine\ORM\QueryBuilder $queryBuilder) {
		$this->setList($queryBuilder->getQuery()->getResult());
		$this->dataSource = $queryBuilder;
	}

	public static function __callStatic($name, $arguments) {

		if(Strings::startsWith($name, 'getPairs')) {
			$name = substr($name, 8);
			if($name == '') $name = 'All';
			$name = 'get' . $name;
			$key = array_shift($arguments);
			$value = array_shift($arguments);

			$list = call_user_func_array(array('static', $name), $arguments);
			$pairs = static::_getPairs($list, $key, $value);

			return $pairs;
		}
		
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

	private static function pripareCriteria($criteria) {
		$return = array();
		foreach ($criteria as $key => $value) {
			if($value instanceof Service || $value instanceof Entity) {
				$value = $value->id;
			} else if(is_array($value)) {
				$value = static::pripareCriteria($value);
			}
			$return[$key] = $value;
			
		}
		return $return;
	}

	public static function getAll(array $orderBy = NULL, $limit = NULL, $offset = NULL) {
		$entityName = static::getMainEntityName();

		$serviceList = new static;
		$qb = $serviceList->getEntityManager()->createQueryBuilder();
		$qb->select('e')
			->from($entityName, 'e');
		if($orderBy) {
			foreach ($orderBy as $key => $value) {
				$qb->addOrderBy('e.'.$key, $value);
			}
		}
		if($limit) $qb->setMaxResults($limit);
		if($offset) $qb->setFirstResult($offset);


		$serviceList->setDataSource($qb);
		return $serviceList;
	}

	public static function getBy(array $criteria, array $orderBy = NULL, $limit = NULL, $offset = NULL) {
		$entityName = static::getMainEntityName();

		$serviceList = new static;
		$qb = $serviceList->getEntityManager()->createQueryBuilder();
		$qb->select('e')
			->from($entityName, 'e');


		foreach (static::pripareCriteria($criteria) as $key => $value) {
			if(is_array($value)) {
				$qb->andWhere($qb->expr()->in('e.'.$key, $value));
			} else {
				$qb->andWhere('e.'.$key.' = :'.$key)
					->setParameter($key, $value);
			}
		}


		if($orderBy) {
			foreach ($orderBy as $key => $value) {
				$qb->addOrderBy('e.'.$key, $value);
			}
		}
		if($limit) $qb->setMaxResults($limit);
		if($offset) $qb->setFirstResult($offset);


		$serviceList->setDataSource($qb);
		return $serviceList;
	}

	public static function getByIn($nameBy, $nameIn, $by, array $in, array $orderBy = NULL, $limit = NULL, $offset = NULL) {
		$entityName = static::getMainEntityName();

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
		$serviceList->setDataSource($qb);

		return $serviceList;

	}


	/** 
	 * return array
	 */
	public static function getPairs($keyName, $valueName = NULL, $criteria = NULL, $orderBy = NULL, $limit = NULL, $offset = NULL) {
		$serviceList = self::_getPairs($keyName, $valueName, $criteria, $orderBy, $limit, $offset);
		$return = array();

		foreach($serviceList as $item) {
			$key = array_shift($item);
			$value = array_shift($item);

			$return[$key] = $value;
		}

		return $return;
	}

	/** 
	 * return array
	 */
	public static function getTranslatedPairs($keyName, $valueName, $criteria = NULL, $orderBy = NULL, $limit = NULL, $offset = NULL) {
		if(isset($orderBy[$valueName])) $orderByName = true;
		else $orderByName = false;
		$valueName = array($valueName, 'id');
		$serviceList = self::_getPairs($keyName, $valueName, $criteria, $orderBy, $limit, $offset);

		$translator = Service::getTranslator();
		$return = array();

		foreach($serviceList as $item) {
			$return[$item['key']] = $translator->translate($item['value']);
		}

		if($orderByName) sort($return);

		return $return;
	}
 
	protected static function _getPairs($keyName, $valueName = NULL, $criteria = NULL, $orderBy = NULL, $limit = NULL, $offset = NULL) {
		$valuePropertyName = NULL;
		if(is_array($valueName) || $valueName instanceof \Traversable) {
			$valueNameTemp = (array) $valueName;
			$valueName = array_shift($valueNameTemp);
			$valuePropertyName = array_shift($valueNameTemp);
		}

		$entityName = static::getMainEntityName();

		$serviceList = new static;
		$qb = $serviceList->getEntityManager()->createQueryBuilder();

		if($valuePropertyName) {
			$select = array('e.'.$keyName.' AS key', 'p.'.$valuePropertyName.' AS value');
		} else {
			$select = array('e.'.$keyName.' AS key', 'e.'.$valueName.' AS value');
		}
		$qb->select($select)
			->from($entityName, 'e');

		if($valuePropertyName) $qb->join('e.'.$valueName, 'p');

		if(is_array($criteria) || $criteria instanceof \Traversable) {
			foreach (static::pripareCriteria($criteria) as $key => $value) {
				if(is_array($value) || $value instanceof \Traversable) {
					$qb->andWhere($qb->expr()->in('e.'.$key, $value));
				} else {
					$qb->andWhere('e.'.$key.' = :'.$key)
						->setParameter($key, $value);
				}
			}
		}


		if($orderBy) {
			foreach ($orderBy as $key => $value) {
				$qb->addOrderBy('e.'.$key, $value);
			}
		}
		if($limit) $qb->setMaxResults($limit);
		if($offset) $qb->setFirstResult($offset);


		$serviceList->setDataSource($qb);

		return $serviceList;
	}

	protected function setList($list) {
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
	protected static function getEntityManager() {
		return self::$em;
	}

	/**
	 * Alias na entity manazera
	 * @return EntityManager
	 */
	protected static function getEm() {
		return self::getEntityManager();
	}


	public function returnAs($returnAs) {
		$this->returnAs = $returnAs;

		return $this;
	}

	public function toArray($keyName = NULL, $valueName = NULL) {
		$array = array();
		foreach ($this as $key => $value) {
			$array[$keyName] = $valueName ? $value[$valueName] : $value; 
		}
		return $array;
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
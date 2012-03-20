<?php

namespace Tra\Services;

use Nette, 
	Tra, 
	Entity, 
	Nette\ObjectMixin, 
	Nette\MemberAccessException,
	Doctrine\ORM\EntityManager,
	Tra\Services\ServiceLoader;

/**
 * Abstrakcia mocnej vrstvy sluzba
 */
abstract class Service extends Nette\Object implements IService {
	
	/**
	 * @var string
	 */
	const MAIN_ENTITY_NAME = null;
	
	/**
	 * @var Entity
	 */
	protected $mainEntity = false;

	/**
	 * @var Reflector
	 */
	protected $reflector = null;

	/**
	 * @var EntityManager
	 */
	private static $em = null;

	/**
	 * @var bool
	 */
	private static $flush = true;

	/**
	 * @var bool
	 */
	private $isPersist = false;

	/**
	 * @param bool
	 */
	public function __construct($new = true) {
		if ($this->getEntityManager() === null) {
			throw new \Exception("Nie je dostupny EntityManager");
		}
		if ($new) {
			$entityName = $this->getMainEntityName();
			$this->mainEntity = new $entityName;
		}
	}

	/**
	 * Nastavenie entity manazera
	 * @param EntityManager
	 */
	public static function setEntityManager(EntityManager $em) {
		self::$em = $em;
	}

	/**
	 * Pozastavi okamzite posielanie sql dotazov
	 */
	public static function preventFlush() {
		self::$flush = false;
	}
	
	/**
	 * Vykona odoslanie pozastavenych sql dotazov
	 */
	public static function flush() {
		self::$em->flush();
		self::$flush = true;
	}
	
	/**
	 * Je pozielanie sql dotazov povolene?
	 * @return bool
	 */
	public static function isFlushable() {
		return self::$flush;
	}

	/**
	 * Ziska jedinecnu instanciu servisy
	 * @param integer
	 * @return IService
	 */
	public static function get($id) {
		$key = get_called_class() . '#' . $id;

		if (ServiceLoader::exists($key)) {
			return ServiceLoader::get($key);
		}
		$service = new static(false);
		$service->load($id);
		ServiceLoader::set($key, $service);
		return $service;
	}
	
	/**
	 * Setter
	 * @param string
	 * @param mixed
	 */
	public function __set($name, $value) {
		if ($value instanceof Service) {
			$this->mainEntity->$name = $value->getMainEntity();
		}
		if ($this->mainEntity instanceof Entity) {
			$this->mainEntity->$name = $value;
		}
	}

	/**
	 * Getter
	 * @param string
	 * @param mixed
	 * @return mixed
	 */
	public function &__get($name) {
		if ($this->mainEntity instanceof Entity) {
			try {
				return ObjectMixin::get($this->mainEntity, $name);
			} catch (MemberAccessException $e) {}
		}

		return ObjectMixin::get($this, $name);
	}

	/**
	 * Magia
	 * @param string
	 * @param mixed
	 */
	public function __call($name, $arguments) {
		try {
			if($this->mainEntity instanceof Entity) {
				if(count($arguments) == 1) {
					$first = reset($arguments);
					if($first instanceof Service) {
						$this->mainEntity->{$name}($first->mainEntity);
						return $this;
					} else {
						$this->mainEntity->{$name}($first);
						return $this;
					}
				} else if(count($arguments) == 0) {
					$this->mainEntity->{$name}();
					return $this;
				}
			}
		} catch (MemberAccessException $e) {}
	}

	/**
	 * Ziskanie hlavnej entity
	 * @return Entity
	 */
	public function getMainEntity() {
		if (!$this->mainEntity) {
			throw new \Exception("Este nebola zadana `mainEntity`");
		}
		
		return $this->mainEntity;
	}

	/**
	 * Ziskanie nazvu hlavnej entity
	 * @return string
	 */
	public function getMainEntityName() {
		if (!static::MAIN_ENTITY_NAME) {
			throw new \Exception("Este nebola zadana `mainEntity`, preto nemozem ziskat jej nazov");
		}
		
		return static::MAIN_ENTITY_NAME;
	}

	/**
	 * Ziskanie reflektora
	 * @return Reflector
	 */
	public function getReflector() {
		if ($this->reflector === null) {
			$this->reflector = new Reflector($this);
		}
		return $this->reflector;
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
	 * Ziskanie zoznamu
	 * @return array
	 */
	public function getList($class, $key, $value) {
		return $this->getEm()->getRepository($class)->fetchPairs($key, $value);
	}

	/**
	 * Ziskanie datasource
	 * @return Query
	 */
	public function getDataSource() {
		$query = $this->getEm()->createQueryBuilder();
		$query->select('e')->from($this->getMainEntityName(), 'e');
		return $query;
	}

	/**
	 * Ziskanie hlavnej entity z entity manazera
	 * @param integer
	 */
	protected function load($id) {
		if ($entity = $this->getEm()->find($this->getMainEntityName(), $id)) {
			$this->isPersist = true;
			$this->mainEntity = $entity;
		}
	}

	/**
	 * Ulozenie zmien (vytvorenie/aktualizovanie)
	 */
	public function save() {
		try {
			if ($this->mainEntity instanceof Entity) {
				if (!$this->isPersist) {
					$this->getEm()->persist($this->mainEntity);
				}
				if ($this->isFlushable()) {
					$this->getEm()->flush();
				}
			}
		} catch (\PDOException $e) {
			throw new \Tra\Services\ServiceException($e->getMessage());
		}
	}

	/**
	 * Zmazanie hlavnej entity
	 */
	public function delete() {
		try {
			if ($this->mainEntity instanceof Entity) {
				$this->getEm()->remove($this->mainEntity);
				//TODO: netreba sa pytat tiez na $this->isFlushable() ?
				$this->getEm()->flush();
			}
		} catch (\PDOException $e) {
			throw new \Tra\Services\ServiceException($e->getMessage());
		}
	}

	/**
	 * Pripravi data
	 */
	public function prepareData(Tra\Forms\Form $form) {
		$assocations = $this->getReflector()->getAssocations();
		$values = $form->getValues();

		foreach ($assocations as $entity => $columns) {
			$container = $form->getComponent($entity);
			foreach ($columns as $name => $target) {
				$control = $container->getComponent($name);
				$values->{$entity}->{$name} = $this->em->find($target, $control->getValue());
			}
		}
		return $values;
	}
	
	/**
	 * Pripravi data pre grid
	 */
	public function prepareGridData(Tra\Forms\Form $form) {
		$assocations = $this->getReflector()->getAssocations();
		$values = $form->getValues();

		foreach ($assocations as $entity => $columns) {
			$container = $form->getComponent($entity);
			foreach ($columns as $name => $target) {
				$control = $container->getComponent($name);
				$values->{$entity}->{$name} = $this->em->find($target, $control->getValue());
			}
		}
		return $values;
	}
}

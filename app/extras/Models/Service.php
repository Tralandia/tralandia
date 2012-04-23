<?php

namespace Extras\Models;

use Nette, 
	Nette\ObjectMixin, 
	Nette\MemberAccessException,
	Tra\Utils\Strings,
	Doctrine\ORM\EntityManager;

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
	 * @var array
	 */
	protected $currentMask = null;

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
	 * @var object
	 */
	public static $translator = null;

	/**
	 * @param bool
	 */
	protected function __construct($new = true) {
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
	public static function flush($pernament = TRUE) {
		self::$em->flush();
		ServiceLoader::flushStack();
		if($pernament) {
			self::$flush = true;
		}
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
	 * @param integer|Entity
	 * @return IService
	 */
	public static function get($value = NULL) {
		$mainEntityName = static::getMainEntityName();

		if ($value instanceof $mainEntityName) {
			if($value->getId() > 0) {
				$key = get_called_class() . '#' . $value->getId();
			} else {
				$service = new static(false);
				$service->load($value);
				return $service;
			}
		} else if(is_numeric($value)) {
			$key = get_called_class() . '#' . $value;
		} else if($value === NULL) {
			return new static();
		} else {
			throw new \Nette\InvalidArgumentException('Argument $value does not match with the expected value');
		}

		if (ServiceLoader::exists($key)) {
			$service = ServiceLoader::get($key);
		} else {
			$service = new static(false);
			$service->load($value);
			ServiceLoader::set($key, $service);
		}

		if($service->getMainEntity() instanceof $mainEntityName) {
			return $service;
		} else {
			// throw new ServiceException('Zle inicializovane servisa: '.get_called_class());
			return null;
		}

	}
	
	/**
	 * Setter
	 * @param string
	 * @param mixed
	 */
	public function __set($name, $value) {
		$method = 'set'.Strings::firstUpper($name);
		if(method_exists($this, $method)){
			$this->{$method}($value);
		}else if ($value instanceof Service) {
			$this->mainEntity->{$name} = $value->getMainEntity();
		}else {
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
		$method = 'get'.Strings::firstUpper($name);
		if(method_exists($this, $method)){
			$return = $this->{$method}();
			return $return;
		}else if ($this->mainEntity instanceof Entity) {
			try {
				return ObjectMixin::get($this->mainEntity, $name);
			} catch (MemberAccessException $e) {}
		}

		return ObjectMixin::get($this, $name);
	}



	# @todo @brano je toto spravne ?
	# toto iste je aj v Entity.php
	public function __isset($name) {
		// toto mi nefungovalo spravne
		// $isset = ObjectMixin::has($this, $name);
		// if(!$isset) {
		// 	$isset = isset($this->getMainEntity()->{$name});
		// }

		// toto uz ide OK
		return $this->{$name} !== NULL;
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
					return $this->mainEntity->{$name}();
				}
			}
		} catch (MemberAccessException $e) {}
		
		return ObjectMixin::call($this, $name, $arguments);
	}


	public static function __callStatic($name, $arguments) {
		list($nameTemp, $name1, $name2) = Strings::match($name, '~^getBy([A-Za-z]+)And([A-Za-z]+)$~');
		if($nameTemp && $name1 && $name2) {
			$name1 = Strings::firstLower($name1);
			$name2 = Strings::firstLower($name2);
			$params = array(
				$name1 => array_shift($arguments),
				$name2 => array_shift($arguments),
			);
			return static::getBy($params);
		} else if(Strings::startsWith($name, 'getBy')) {
			$name = str_replace('getBy', '', $name);
			$name = Strings::firstLower($name);
			return static::getBy(array($name => $arguments));
		} else {
			return parent::__callStatic($name, $arguments);
		}
	}


	public static function getBy($criteria) {
		foreach ($criteria as $key => $value) {
			if($value instanceof Service || $value instanceof Entity) {
				$criteria[$key] = $value->id;
			}
		}
		$repo = static::getEm()->getRepository(static::getMainEntityName());
		$result = $repo->findOneBy($criteria);
		return $result ? static::get($result) : NULL;
	}


	/**
	 * Ziskanie hlavnej entity
	 * @return Entity
	 */
	public function getMainEntity() {
		if (!$this->mainEntity) {
			# @toto david, toto som zakomentoval lebo mi to hadzalo error vid. riadok 125
			// throw new \Exception("Este nebola zadana `mainEntity`");
		}
		
		return $this->mainEntity;
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

	/**
	 * Ziskanie translatora
	 * @return Translator
	 */
	public static function getTranslator() {
		return self::$translator;
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
	 * @param integer|Entity
	 */
	protected function load($value) {
		if ($value instanceof Entity) {
			$this->isPersist = true;
			$this->mainEntity = $value;
			return;
		}

		if ($entity = $this->getEm()->find($this->getMainEntityName(), $value)) {
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
					self::flush();
					ServiceLoader::set(get_class($this) . '#' . $this->getId(), $this);
				} else {
					ServiceLoader::addToStack($this);
				}
			}
		} catch (\PDOException $e) {
			throw new ServiceException($e->getMessage());
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
				self::flush();
			}
		} catch (\PDOException $e) {
			throw new ServiceException($e->getMessage());
		}
	}

	public function setCurrentMask($mask) {
		$this->currentMask = $mask;
	}

	public function getCurrentMask() {
		return $this->currentMask;
	}

	/**
	 * Ziskanie datasource
	 * @return Query
	 */
	public function getDefaultsData($mask) {
		$data = array();
		foreach ($mask->fields as $property) {
			$ui = $property->ui;
			$name = $ui->name;
			if(!$this->{$name}) {
				$data[$name] = NULL;
			} else {
				if(isset($property->targetEntity)) {
					$targetEntity = $property->targetEntity;
					if ($targetEntity->name == 'Entity\\Dictionary\\Phrase') {
						$data[$name] = $this->getTranslator()->translate($this->{$name});
					} else if($targetEntity->associationType == Reflector::MANY_TO_MANY) {
						$data[$name] = array();
						foreach ($this->{$name}->toArray() as $key => $value) {
							$data[$name][] = array(
								'key' => $value->{$targetEntity->primaryKey},
								'value' => $value->{$targetEntity->primaryValue},
							);
						}
					} else if($targetEntity->associationType == Reflector::ONE_TO_MANY) {
						// @todo method or operation is not implemented
						throw new \Nette\NotImplementedException('Requested method or operation is not implemented');
					} else if($targetEntity->associationType == Reflector::MANY_TO_ONE) {
						$data[$name] = $this->{$name}->{$targetEntity->primaryKey};
					} else if($targetEntity->associationType == Reflector::ONE_TO_ONE) {
						$property = $targetEntity->primaryValue;
						//debug($this->{$name}->translations, $property);

						$data[$name] = $this->{$name}->{$property};
					} else {
						$data[$name] = $this->{$name};
						// @todo method or operation is not implemented
						//throw new \Nette\NotImplementedException('Requested method or operation is not implemented');
					}

				} else {
					$data[$name] = $this->{$name};
				}				
			}
		}
		//debug($data);
		return $data;
	}

	public function updateFormData($mask, $formValues) {
		debug($formValues);
		foreach ($mask->fields as $property) {
			$ui = $property->ui;
			if($ui->disabled) continue;
			$name = $ui->name;
			if(array_key_exists($name, $formValues)) {
				$formValue = $formValues[$name];
				if(isset($property->targetEntity)) {
					$targetEntity = $property->targetEntity;
					if($targetEntity->name == 'Entity\\Dictionary\\Phrase') {
						// fraza sa needituje cez serisu
					} else if($targetEntity->associationType == Reflector::ONE_TO_ONE) {
						$this->{$name}->{$targetEntity->primaryValue} = $formValue;
					} else if($targetEntity->associationType == Reflector::MANY_TO_MANY) {
						$this->{$name}->clear();
						if(is_array($formValue)) {
							foreach ($formValue as $key => $value) {
								$serviceName = $targetEntity->serviceName;
								if($value = $serviceName::get($value)) {
									$this->{'add' . ucfirst($ui->nameSingular)}($value->getMainEntity());
								}
							}
						}
					} else if($targetEntity->associationType == Reflector::ONE_TO_MANY) {
						// @todo method or operation is not implemented
						throw new \Nette\NotImplementedException('Requested method or operation is not implemented');
					} else if($targetEntity->associationType == Reflector::MANY_TO_ONE) {
						$serviceName = $targetEntity->serviceName;
						$this->{$name} = $serviceName::get($formValue);
					} else {
						// @todo method or operation is not implemented
						throw new \Nette\NotImplementedException('Requested method or operation is not implemented');
					}
				} else {
					$this->{$name} = $formValue;
				}
			}
		}
		$this->save();
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

<?php

namespace Extras\Models;

use Nette, 
	Extras\Entity, 
	Nette\ObjectMixin, 
	Nette\MemberAccessException,
	Doctrine\ORM\EntityManager;

abstract class Service extends Nette\Object implements IService {
	
	const MAIN_ENTITY_NAME = null;
	protected $mainEntity = false;
	protected $reflector = null;
	private static $em = null;
	private static $flush = true;

	public function __construct() {

	}

	public static function setEntityManager(EntityManager $em) {
		self::$em = $em;
	}

	public static function preventFlush() {
		self::$flush = false;
	}
	
	public static function flush() {
		self::$em->flush();
		self::$flush = true;
	}
	
	public static function isFlushable() {
		return self::$flush;
	}
	
	public function __set($name, $value) {
		if ($value instanceof Service) {
			return $this->mainEntity->$name = $value->getMainEntity();
		}
		if ($this->mainEntity instanceof Entity) {
			return $this->mainEntity->$name = $value;
		}
	}

	public function &__get($name) {
		if ($this->mainEntity instanceof Entity) {
			try {
				return ObjectMixin::get($this->mainEntity, $name);
			} catch (MemberAccessException $e) {}
		}

		return ObjectMixin::get($this, $name);
	}

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

	public function getMainEntity() {
		if (!$this->mainEntity) {
			throw new \Exception("Este nebola zadana `mainEntity`");
		}
		
		return $this->mainEntity;
	}

	public function getMainEntityName() {
		if (!static::MAIN_ENTITY_NAME) {
			throw new Exception("Este nebola zadana `mainEntity`, preto nemozem ziskat jej nazov");
		}
		
		return static::MAIN_ENTITY_NAME;
	}
	
	public function getReflector() {
		if ($this->reflector === null) {
			$this->reflector = new Reflector($this);
		}
		return $this->reflector;
	}

	public static function getEntityManager() {
		return self::$em;
	}

	public static function getEm() {
		return self::getEntityManager();
	}
	
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

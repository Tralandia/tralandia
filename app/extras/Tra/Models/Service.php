<?php

namespace Tra\Services;
use Nette, 
	Tra, 
	Entity, 
	Nette\ObjectMixin, 
	Nette\MemberAccessException;

abstract class Service extends Nette\Object implements IService {
	
	const MAIN_ENTITY_NAME = null;
	protected $mainEntity = false;
	protected $reflector = null;
	private $em = null;

	public function __construct() {

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
		if($this->mainEntity instanceof Entity) {
			try {
				if(count($arguments) == 1) {
					$first = reset($arguments);
					if($first instanceof Service) {
						$this->mainEntity->{$name}($first->mainEntity);
						return $this;
					}
				}
			} catch (MemberAccessException $e) {}
		}
	}

	public function getMainEntity() {
		if (!$this->mainEntity) {
			throw new Exception("Este nebola zadana `mainEntity`");
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

	public function getEntityManager() {
		if ($this->em == null) {
			// TODO: treba vyriesit ako sa zbavit Environmentu
			$context = Nette\Environment::getContainer();
			$this->em = $context->getService('doctrine')->entityManager;
		}
		
		return $this->em;
	}

	public function getEm() {
		return $this->getEntityManager();
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

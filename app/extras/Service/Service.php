<?php

namespace Extras\Service;

use Nette, Doctrine, Extras, IteratorAggregate;

/**
 * Vrstva sluzieb
 * @author Branislav Vaculčiak
 */
abstract class Service extends Nette\Object implements IService, IteratorAggregate {

	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $entityManager = null;

	/**
	 * @var Extras\IEntity
	 */
	protected $entity = null;

	/**
	 * @param Doctrine\ORM\EntityManager
	 * @param IEntity
	 */
	public function __construct(Doctrine\ORM\EntityManager $entityManager, Extras\IEntity $entity) {
		$this->entityManager = $entityManager;
		$this->entity = $entity;
	}

	/**
	 * Dynamicke zavolanie metod nad entitou
	 * @param string
	 * @param array
	 */
	public function __call($name, $args) {
		$class = new Nette\Reflection\ClassType($this->entity);

		if ($class->hasMethod($name)) {
			$method = $class->getMethod($name);
			$op1 = substr($method->getName(), 3);
			$op2 = substr($method->getName(), 5);
			$read = array('get' . $op1);
			$write = array('set' . $op1, 'add' . $op1, 'remove' . $op2);

			if ($this->getReflection()->hasAnnotation('access')) {
				$access = $this->getReflection()->getAnnotation('access');

				if (in_array($method->getName(), $write) && !in_array('write', (array)$access)) {
					throw new Nette\MemberAccessException("Call to not allowed method $class->name::$name().");
				}
				if (in_array($method->getName(), $read) && !in_array('read', (array)$access)) {
					throw new Nette\MemberAccessException("Call to not allowed method $class->name::$name().");
				}

				return $method->invokeArgs($this->entity, $args);
			} else {
				return $method->invokeArgs($this->entity, $args);
			}
		}
		throw new Nette\MemberAccessException("Call to undefined method $class->name::$name().");
	}

	/**
	 * Getter nad entitou
	 * @param  string  property name
	 * @return mixed   property value
	 * @throws MemberAccessException if the property is not defined.
	 */
	public function &__get($name) {
		return Nette\ObjectMixin::get($this->entity, $name);
	}

	/**
	 * Setter nad entitou
	 * @param  string  property name
	 * @param  mixed   property value
	 * @return void
	 * @throws MemberAccessException if the property is not defined or is read-only
	 */
	public function __set($name, $value) {
		return Nette\ObjectMixin::set($this->entity, $name, $value);
	}

	/**
	 * Je vlastnost definovana?
	 * @param  string  property name
	 * @return bool
	 */
	public function __isset($name) {
		return Nette\ObjectMixin::has($this->entity, $name);
	}

	/**
	 * Uvolnenie vlastnosti
	 * @param  string  property name
	 * @return void
	 * @throws MemberAccessException
	 */
	public function __unset($name) {
		Nette\ObjectMixin::remove($this->entity, $name);
	}

	/**
	 * Vrati iterator entity
	 * @return ArrayIterator
	 */
	public function getIterator() {
		return $this->entity->getIterator();
	}

	/**
	 * Vrati entity manazera
	 * @return Doctrine\ORM\EntityManager
	 */
	public function getEntityManager() {
		return $this->entityManager;
	}

	/**
	 * Vrati repozitar pre entitu
	 * @return Doctrine\ORM\EntityRepository
	 */
	public function getRepository() {
		return $this->entityManager->getRepository(get_class($this->entity));
	}
}
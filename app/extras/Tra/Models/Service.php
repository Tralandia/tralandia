<?php

namespace Tra\Services;
use Nette, Tra;

abstract class Service extends Nette\Object implements IService {
	
	protected $mainEntity = null;
	protected $reflector = null;
	private $em = null;

	public function __construct() {

	}
	
	public function getMainEntity() {
		if ($this->mainEntity === null) {
			throw new Exception("Exte nebola zadana hlavna entita");
		}
		
		return $this->mainEntity;
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

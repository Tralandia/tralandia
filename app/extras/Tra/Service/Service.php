<?php

namespace Tra\Services;
use Nette;

abstract class Service extends Nette\Object implements IService {
	
	protected $reflector = null;
	private $em = null;

	public function __construct() {

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
}

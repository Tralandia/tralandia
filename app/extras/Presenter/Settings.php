<?php

namespace Extras\Presenter;

use Nette;

class Settings extends Nette\Object {

	/** @var string */
	private $parameters = array();

	/** @var string */
	private $defaults = array();

	/** @var Nette\DI\Container */
	private $container;

	/** @var string */
	private $entityClass;

	/**
	 * @param array
	 * @param Nette\DI\Container
	 */
	public function __construct(array $defaults = array(), $entityClass, Nette\DI\Container $container)
	{
		$this->defaults = $defaults;
		$this->container = $container;
		$this->entityClass = $entityClass;
	}

	public function __set($name, $value) {
		$this->parameters[$name] = $value;
	}

	public function &__get($name) {
		if (!$var = $this->defaults[$name]) {
			return parent::__get($name);
		}

		if (preg_match('/\$([a-z0-9]+)?\$/', $var, $matches)) {
			if (isset($this->parameters[$matches[1]])) {
				$var = str_replace($matches[0], $this->parameters[$matches[1]], $var);
			}
		}
		return $var;
	}

	public function getEntityClass() {
		return $this->entityClass;
	}
}
<?php

namespace Extras;

use Nette;

class DynamicPresenterFactory extends Nette\Application\PresenterFactory {
	
	/** @var Nette\DI\IContainer */
	private $context;

	private $dynamicPresenters = array();
	
	public static function factory(Nette\DI\IContainer $context) {
		if (!($context->robotLoader instanceof Nette\Loaders\RobotLoader)) {
			throw new Nette\InvalidStateException('DynamicPresenterFactory nema nastaveny RobotLoader');
		}
		return new self(APP_DIR, $context);
	}

	public function __construct($baseDir, Nette\DI\IContainer $context) {
		parent::__construct($baseDir, $context);
		$this->context = $context;
	}

	
	public function createPresenter($name) {
		$class = $this->formatPresenterClass($name);
		if (!array_key_exists($class, $this->context->robotLoader->getIndexedClasses())) {
			$this->createDynamicPresenter($class);
		}
		return parent::createPresenter($name);
	}
	
	private function createDynamicPresenter($class) {
		$class = explode('\\', $class);
		$class = array_pop($class);
		debug($class);
		if(!isset($this->dynamicPresenters[$class])) {
			// @todo spravit overovanie ci sa ma takyto dynamicky presenter vytvorit
			eval("namespace AdminModule { class $class extends AdminPresenter {} }");
			$this->dynamicPresenters[$class] = TRUE;
		}
	}


	/**
	 * @param  string  presenter name
	 * @return string  class name
	 * @throws InvalidPresenterException
	 */
	public function getPresenterClass(& $name)
	{
		if (isset($this->cache[$name])) {
			list($class, $name) = $this->cache[$name];
			return $class;
		}

		if (!is_string($name) || !Nette\Utils\Strings::match($name, "#^[a-zA-Z\x7f-\xff][a-zA-Z0-9\x7f-\xff:]*$#")) {
			throw new InvalidPresenterException("Presenter name must be alphanumeric string, '$name' is invalid.");
		}

		$class = $this->formatPresenterClass($name);

		if (!class_exists($class)) {
			// internal autoloading
			$file = $this->formatPresenterFile($name);
			if (is_file($file) && is_readable($file)) {
				Nette\Utils\LimitedScope::load($file, TRUE);
			}

			// @todo @hack !!!
			if (!class_exists($class)) {
				$this->createDynamicPresenter($class);
			}
			
			if (!class_exists($class)) {
				throw new InvalidPresenterException("Cannot load presenter '$name', class '$class' was not found in '$file'.");
			}
		}

		$reflection = new Nette\Reflection\ClassType($class);
		$class = $reflection->getName();

		if (!$reflection->implementsInterface('Nette\Application\IPresenter')) {
			throw new InvalidPresenterException("Cannot load presenter '$name', class '$class' is not Nette\\Application\\IPresenter implementor.");
		}

		if ($reflection->isAbstract()) {
			throw new InvalidPresenterException("Cannot load presenter '$name', class '$class' is abstract.");
		}

		// canonicalize presenter name
		$realName = $this->unformatPresenterClass($class);
		if ($name !== $realName) {
			if ($this->caseSensitive) {
				throw new InvalidPresenterException("Cannot load presenter '$name', case mismatch. Real name is '$realName'.");
			} else {
				$this->cache[$name] = array($class, $realName);
				$name = $realName;
			}
		} else {
			$this->cache[$name] = array($class, $realName);
		}

		return $class;
	}

}

class InvalidPresenterException extends \Exception {

}
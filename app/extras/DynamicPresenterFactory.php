<?php

namespace Extras;


class DynamicPresenterFactory extends Nette\Application\PresenterFactory {
	
	/** @var Nette\DI\IContainer */
	private $context;
	
	public function __construct($baseDir, Nette\DI\IContainer $context) {
		parent::__construct($baseDir, $context);
		$this->context = $context;
	}

	public static function factory(Nette\DI\IContainer $context) {
		if (!($context->robotLoader instanceof Nette\Loaders\RobotLoader)) {
			throw new Nette\InvalidStateException('DynamicPresenterFactory nema nastaveny RobotLoader');
		}
		return new self(APP_DIR, $context);
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
		eval("namespace AdminModule { class $class extends AdminPresenter {} }");
	}
}
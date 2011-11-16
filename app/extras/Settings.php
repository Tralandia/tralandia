<?php

use Nette\DI;

class Settings extends \Nette\Object {
	
	protected $settingsDir;
	protected $presenter;
	protected $name;
	
	public static function factory(DI\Container $container) {
		$presenter = $container->getService('application')->getPresenter();
		return new self($presenter, $container->params['settingsDir']);
	}
	
	public function __construct($presenter, $settingsDir) {
		$this->presenter = $presenter;
		$this->settingsDir = $settingsDir;
		$this->name = str_replace('Presenter', null, $presenter->getReflection()->getShortName());
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function getPresenterName() {
		return $this->presenter->getName();
	}
	
	public function getServiceClass() {
		return '\\Tra\\Services\\' . $this->getName();
	}
}
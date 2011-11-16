<?php

use Nette\DI,
	Nette\ArrayHash,
	Nette\Config\Config;

class PresenterSettings extends \Nette\Object {
	
	protected $params;
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
		$this->params = ArrayHash::from(Config::fromFile($this->settingsDir . '/presenters/' . strtolower($this->getName()) . '.neon', 'common'));
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
	
	public function getParams() {
		return $this->params;
	}
	
	public function getTitle() {
		return isset($this->params->title) ? $this->params->title : $this->name;
	}
}
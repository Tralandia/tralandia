<?php

namespace Extras;


use Nette\DI,
	Nette\ArrayHash,
	Nette\Utils\Neon;

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
		
		$config = new Nette\Config\Loader;
		$this->params = ArrayHash::from($config->load($this->settingsDir . '/presenters/' . strtolower($this->getName()) . '.neon', 'common'));
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

	public function getH1() {
		return isset($this->params->h1) ? $this->params->h1 : $this->title;
	}
}
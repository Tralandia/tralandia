<?php

namespace Extras;


use Nette\DI,
	Nette\ArrayHash,
	Nette\Utils\Neon,
	Nette\Utils\Arrays;

class PresenterSettings extends \Nette\Object {
	
	const DEFAULT_CONFIG_SECTION = 'common';

	protected $params;
	protected $settingsDir;
	protected $presenter;
	protected $name;
	protected $configName;
	protected $configSection = self::DEFAULT_CONFIG_SECTION;
	
	public static function factory(DI\Container $container) {
		$presenter = $container->getService('application')->getPresenter();
		return new self($presenter, $container->params['settingsDir']);
	}
	
	public function __construct($presenter, $settingsDir) {
		$this->presenter = $presenter;
		$this->settingsDir = $settingsDir;
		$this->configName = strtolower(str_replace('Presenter', null, $presenter->getReflection()->getShortName()));
	}
	
	public function getName() {
		if(!$this->name) {
			$this->name = $this->getParams()->service;
		}
		return $this->name;
	}
	
	public function getPresenterName() {
		return $this->presenter->getName();
	}
	
	public function getServiceClass() {
		return $this->getName();
	}

	public function getServiceListClass() {
		return $this->getName() . 'List';
	}
	
	public function getParams() {
		if(!$this->params) {
			$config = $this->getConfig();
			$this->checkConfig($config);
			$this->params = $config;
		}
		return $this->params;
	}
	
	public function getTitle() {
		return isset($this->getParams()->title) ? $this->getParams()->title : $this->name;
	}

	public function getH1() {
		return isset($this->getParams()->h1) ? $this->getParams()->h1 : $this->getTitle();
	}

	protected function getConfig() {
		$config = new \Nette\Config\Loader;

		$params = Arrays::mergeTree(
			$config->load($this->settingsDir . '/presenters/' . $this->configName . '.neon', $this->configSection),
			$config->load($this->settingsDir . '/presenters/baseConfig.neon', 'common')
		);

		return ArrayHash::from($params);
	}

	protected function checkConfig($config) {
		if(!isset($config->repositoryAccessor)) {
			throw new \Nette\InvalidArgumentException('v configu si nezadal "repositoryAccessor"');
		}
		if(!isset($config->grid->class)) {
			throw new \Nette\InvalidArgumentException('v configu si nezadal "grid:class"');
		}
		return true;
	}
}
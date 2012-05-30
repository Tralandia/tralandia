<?php

namespace AdminModule\Components;

use BaseModule\Components\BaseControl,
	Nette\ArrayHash,
	Tra\Utils\Arrays,
	Tra\Utils\Strings;

class Navigation extends BaseControl {

	protected $navigation;
	protected $autopilotRegime;
	public $user;

	public function render() {
		$template = $this->template;
		$template->setFile(dirname(__FILE__) . '/template.latte');

		$this->autopilotRegime = Strings::endsWith($this->getPresenter()->name, ':Ap');

		$template->leftItems = $this->prepareNavigation($this->getNavigation()->left);
		$template->rightItems = $this->prepareNavigation($this->getNavigation()->right);
		$template->render();
	}

	public function prepareNavigation($navigation){
		foreach ($navigation as $key => $value) {
			if($value === NULL) {
				$navigation->$key = $value = new ArrayHash;
			}
			if(!isset($value->class)) {
				$value->class = '';
			}
			if($this->autopilotRegime) {
				$value->class .= 'inIframe';
			}

			if(!isset($value->label)) {
				$value->label = ucfirst($key);
			}
			if(isset($value->link)) {
				$value->href = $this->presenter->link($value->link,(array) Arrays::get($value, 'linkArgs', NULL));
			} else {
				$value->href = '#';
			}

			if(isset($value->items)) {
				$value->items = $this->prepareNavigation($value->items);
			}
		}
		return $navigation;
	}

	public function getNavigation() {
		if(!$this->navigation){
			$this->navigation = $this->loadNavigation();
			$this->extendNavigation();
		}
		return $this->navigation;
	}

	public function extendNavigation() {
		$navigation = $this->getNavigation();
		if($this->user->isLoggedIn()) {
			$identity = $this->user->getIdentity();
			$navigation->right->account->label = $identity->login;
		} 
	}

	private function loadNavigation() {
		$config = new \Nette\Config\Loader;

		return ArrayHash::from($config->load(APP_DIR . '/configs/navigation.neon', 'common'));
	}

}
<?php

namespace AdminModule\Components;

use BaseModule\Components\BaseControl,
	Nette\ArrayHash,
	Tra\Utils\Arrays;

class Navigation extends BaseControl {

	protected $navigation;

	public function render() {
		$template = $this->template;
		$template->setFile(dirname(__FILE__) . '/template.latte');

		$template->leftItems = $this->prepareNavigation($this->getNavigation()->left);
		$template->rightItems = $this->prepareNavigation($this->getNavigation()->right);
		$template->render();
	}

	public function prepareNavigation($navigation){
		foreach ($navigation as $key => $value) {
			if($value === NULL) {
				$navigation->$key = $value = new ArrayHash;
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
		$navigation->right->account->label = 'User Name';
	}

	private function loadNavigation() {
		$config = new \Nette\Config\Loader;

		return ArrayHash::from($config->load(APP_DIR . '/configs/navigation.neon', 'common'));
	}

}
<?php

namespace AdminModule;

use Nette\Environment;
use Nette\Security\User;

abstract class BasePresenter extends \BasePresenter {
	
	protected function startup() {
		$this->autoCanonicalize = FALSE;
		parent::startup();

		if (!$this->user->isLoggedIn()) {
			if ($this->user->getLogoutReason() === User::INACTIVITY) {
				$this->flashMessage('Session timeout, you have been logged out', 'warning');
			}
			$backlink = $this->storeRequest();
			$this->redirect(':Front:Sign:in', array('backlink' => $backlink));
		} else {
			list($model, ) = explode(':', $this->name, 2);
			if (!$this->user->isAllowed($this->name, $this->action) && !$this->user->isAllowed($model.':Base', $this->action)) {
				$this->flashMessage('Hey dude! You don\'t have permissions to view that page.', 'warning');
				$this->restoreRequest($this->getPreviousBackLink());
			}
		}

	}

/*
	public function templatePrepareFilters($tpl) {
		$tpl->registerFilter($latte = new \Nette\Latte\Engine);
		$set = \Nette\Latte\Macros\MacroSet::install($latte->parser);
		$set->addMacro('url', 'echo \Tools::link($control, %node.array);');
	}
*/
	
	public function formatTemplateFiles() {
		$name = $this->getName();
		$presenter = substr($name, strrpos(':' . $name, ':'));
		$module = substr($this->getReflection()->getName(), 0, strpos($this->getReflection()->getName(), '\\'));
		$dir = APP_DIR . '/' . $module;

		return array(
			"$dir/templates/$presenter/$this->view.latte",
			"$dir/templates/$presenter.$this->view.latte",
			"$dir/templates/Admin/$this->view.latte"
		);
	}
	
	public function formatLayoutTemplateFiles() {
		$name = $this->getName();
		$presenter = substr($name, strrpos(':' . $name, ':'));
		$layout = $this->layout ? $this->layout : 'layout';
		$module = substr($this->getReflection()->getName(), 0, strpos($this->getReflection()->getName(), '\\'));
		$dir = APP_DIR . '/' . $module;
		$list = array(
			"$dir/templates/$presenter/@$layout.latte",
			"$dir/templates/$presenter.@$layout.latte",
			"$dir/templates/Admin/@$layout.latte"
		);
		do {
			$list[] = "$dir/templates/@$layout.latte";
			$dir = dirname($dir);
		} while ($dir && ($name = substr($name, 0, strrpos($name, ':'))));
		return $list;
	}

	public function createComponentNavigation($name){
		$navigation = new \AdminModule\Components\Navigation($this, $name);
		$navigation->user = $this->user;
		return $navigation;
	}

	public function actionDecodeNeon() {
		$input = $this->getHttpRequest()->getPost('input', '');
		try {
			$decoded = \Nette\Utils\Neon::decode($input);
			$output = \Nette\Diagnostics\Debugger::dump($decoded, true);
		} catch(\Nette\Utils\NeonException $e) {
			$output = $e->getMessage();
		}

		$this->payload->output = $output;
		$this->sendPayload();
	}
}

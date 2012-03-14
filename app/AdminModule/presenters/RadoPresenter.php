<?php

namespace AdminModule;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\Utils\Strings,
	Tra\Services\Dictionary as D,
	Services as S,
	Services\Log\Change as SLog;

class RadoPresenter extends BasePresenter {

	public function actionDefault() {}
	public function renderDefault() {
		debug($this->context->session);
		$this->template->test = ':)';
	}

}

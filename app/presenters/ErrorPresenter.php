<?php

use Nette\Application\UI\Presenter,
	Nette\Diagnostics\Debugger,
	Nette\Environment;

class ErrorPresenter extends Presenter {

	public function renderDefault($exception) {
		$this->template->back = "javascript:history.back(1);";
		
		if ($this->isAjax()) { // AJAX request? Just note this error in payload.
			Debugger::log($exception, Debugger::ERROR);
			$this->payload->error = TRUE;
			$this->terminate();
		} elseif ($exception instanceof \BadRequestException || $exception instanceof Nette\Application\BadRequestException) {
			$code = $exception->getCode();
			$this->setView('4O4'); // load template 403.phtml or 404.phtml or ... 4xx.phtml
		} else {
			$this->setView('500'); // load template 500.phtml
			Debugger::log($exception, Debugger::ERROR); // and log exception
		}
	}
}
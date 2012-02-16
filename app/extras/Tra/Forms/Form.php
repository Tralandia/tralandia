<?php

namespace Tra\Forms;

use Tra;

class Form extends \CoolForm {
/*
	public function __construct(\Nette\ComponentModel\IContainer $parent = null, $name = null) {
		parent::__construct($parent, $name);
		
		$params = $this->getPresenter()->getRequest()->getParams();
		
			debug($this->getPresenter()->getRequest()->getParams());
		
		$presenter = $this->getPresenter();
		
	debug($this->getPresenter()->link('Rental:edittak'));
		
		//$this->setAction($this->getPresenter()->link('Admin:Rental:submit!'));
		
		$name = $this->lookupPath('Nette\Application\UI\Presenter');
		
		$this->setAction(new \Nette\Application\UI\Link(
			$presenter,
			$name . self::NAME_SEPARATOR . 'submit!',
			array()
		));
	}
*/
	public function getPreparedValues(Tra\Services\Iservice $service) {
		return $service->prepareData($this);
    }

   	public function getParentService() {
		return $this->getPresenter()->getMainService();
    }
}

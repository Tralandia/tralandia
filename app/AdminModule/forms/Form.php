<?php

namespace AdminModule\Forms;

use Nette\Utils\Arrays;

class Form extends \CoolForm {

	public $onLoad = array();


	public $wrappers = array(
		'controls' => array(
			'container' => NULL,
		),
		'pair' => array(
			'container' => 'div class=control-group',
		),
		'control' => array(
			'container' => NULL,

			'description' => 'p class=help-block',
		),
		'label' => array(
			'container' => NULL,
		),
	);

	public function __construct(\Nette\ComponentModel\IContainer $parent = NULL, $name = NULL) {
		parent::__construct($parent, $name);
		
		$this->ajax(FALSE);
		$this->setRenderer(new \Extras\Forms\Rendering\AdminFormRenderer);
		$renderer = $this->getRenderer();
		$renderer->wrappers = Arrays::mergeTree($this->wrappers, $renderer->wrappers);
	}


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
/*	public function getPreparedValues(Tra\Services\Iservice $service) {
		return $service->prepareData($this);
    }

   	public function getParentService() {
		return $this->getPresenter()->getMainService();
    }*/
}

<?php

namespace AdminModule\Components;

use Nette, TwiGrid, Doctrine;

class GridFactory extends Nette\Object {

	private $parameters = array();
	private $translator;
	private $session;
	private $class = '\\AdminModule\\Components\\Grid';

	public function __construct(Nette\Localization\ITranslator $translator, Nette\Http\Session $session) {
		$this->translator = $translator;
		$this->session = $session;
	}

	public function setClass($class) {
		if (!class_exists($class)) {
			throw new \Exception("Neexistuje trieda $class");
		}
		$this->class = $class;
	}

	public function setParameters(array $parameters) {
		$this->parameters = $parameters;
	}

	public function create(Nette\Application\IPresenter $presenter, Doctrine\ORM\EntityRepository $repository) {
		$grid = new TwiGrid\DataGrid($this->session);
		$grid->setTranslator($this->translator);
		return new $this->class($this->parameters, $grid, $presenter, $repository);
	}
}
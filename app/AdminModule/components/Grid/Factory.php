<?php

namespace AdminModule\Components;

use Nette, TwiGrid, Doctrine;

class GridFactory extends Nette\Object {

	private $parameters = array();
	private $translator;
	private $session;

	public function __construct(Nette\Localization\ITranslator $translator, Nette\Http\Session $session) {
		$this->translator = $translator;
		$this->session = $session;
	}

	public function setParameters(array $parameters) {
		$this->parameters = $parameters;
	}

	public function create(Nette\Application\IPresenter $presenter, Doctrine\ORM\EntityRepository $repository) {
		$grid = new TwiGrid\DataGrid($this->session);
		return new Grid($this->parameters, $grid, $presenter, $repository);
	}
}
<?php

namespace AdminModule\Grids;

use AdminModule\Components\AdminGridControl;

class LanguageGrid extends AdminGridControl {

	public function __construct($repository) {
		$this->repository = $repository;
	}

	public function render() {

		$this->template->render();
	}

	public function createComponentGrid()
	{
		$grid = $this->getGrid();

		$grid->addColumn('supported', 'Supported');
		$grid->addColumn('iso', 'Iso');
		$grid->addColumn('name', 'Name');

		return $grid;
	}
}

interface ILanguageGridFactory {

	/**
	 * @return ILanguageGridFactory
	 */
	public function create();
}

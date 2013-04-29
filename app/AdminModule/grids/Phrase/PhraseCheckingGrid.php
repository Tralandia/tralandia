<?php

namespace AdminModule\Grids;

use AdminModule\Components\AdminGridControl;

class PhraseCheckingGrid extends AdminGridControl {

	public function __construct($repository) {
		$this->repository = $repository;
	}

	public function render() {

		$this->template->render();
	}

	public function createComponentGrid()
	{
		$grid = $this->getGrid();

		$grid->addColumn('entityName', 'Entity name');
		$grid->addColumn('entityAttribute', 'Entity attribute');
		$grid->addColumn('required');

		return $grid;
	}

}

interface IPhraseCheckingGridFactory {

	/**
	 * @return IPhraseCheckingGridFactory
	 */
	public function create();
}

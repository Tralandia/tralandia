<?php

namespace AdminModule\Grids;

use AdminModule\Components\AdminGridControl;

class AmenityTypeGrid extends AdminGridControl {

	public function __construct($repository) {
		$this->repository = $repository;
	}

	public function render() {

		$this->template->render();
	}

	public function createComponentGrid()
	{
		$grid = $this->getGrid();

		$grid->addColumn('id');
		$grid->addColumn('name', 'Name');
		$grid->addColumn('iso', 'Iso');
		$grid->addColumn('rounding', 'Rounding');

		return $grid;
	}
}

interface IAmenityTypeGridFactory {

	/**
	 * @return IAmenityTypeGridFactory
	 */
	public function create();
}

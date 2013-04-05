<?php

namespace AdminModule\Grids;

use AdminModule\Components\AdminGridControl;

class LocationTypeGrid extends AdminGridControl {

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

interface ILocationTypeGridFactory {

	/**
	 * @return ILocationTypeGridFactory
	 */
	public function create();
}

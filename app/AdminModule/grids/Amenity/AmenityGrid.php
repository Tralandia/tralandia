<?php

namespace AdminModule\Grids;

use AdminModule\Components\AdminGridControl;

class AmenityGrid extends AdminGridControl {

	public function __construct($repository) {
		$this->repository = $repository;
	}

	public function render() {

		$this->template->render();
	}

	public function createComponentGrid()
	{
		$grid = $this->getGrid();

		$grid->addColumn('slug', 'Slug');
		$grid->addColumn('name', 'Name');
		$grid->addColumn('important', 'Important');
		$grid->addColumn('sorting', 'Sorting');

		return $grid;
	}
}

interface IAmenityGridFactory {

	/**
	 * @return IAmenityGridFactory
	 */
	public function create();
}

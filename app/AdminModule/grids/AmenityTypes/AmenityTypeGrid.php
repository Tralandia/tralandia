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

		$grid->addColumn('name', 'Name');
		$grid->addColumn('slug', 'Slug');
		$grid->addColumn('sorting', 'Sorting');

		return $grid;
	}
}

interface IAmenityTypeGridFactory {

	/**
	 * @return IAmenityTypeGridFactory
	 */
	public function create();
}

<?php

namespace AdminModule\Grids;

use AdminModule\Components\AdminGridControl;

class RentalTypeGrid extends AdminGridControl {

	public function __construct($repository) {
		$this->repository = $repository;
		parent::__construct();
	}

	public function render() {
		$this->template->render();
	}

	public function createComponentGrid()
	{
		$grid = $this->getGrid();

		$grid->addColumn('id');
		$grid->addColumn('name');
		$grid->addColumn('slug');
		$grid->addColumn('classification');

		return $grid;
	}
}

interface IRentalTypeGridFactory {

	/**
	 * @return RentalTypeGrid
	 */
	public function create();
}

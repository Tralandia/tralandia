<?php

namespace AdminModule\Grids;

use AdminModule\Components\AdminGridControl;

class DomainGrid extends AdminGridControl {

	public function __construct($repository) {
		$this->repository = $repository;
	}

	public function render() {

		$this->template->render();
	}

	public function createComponentGrid()
	{
		$grid = $this->getGrid();

		$grid->addColumn('domain', 'Domain');
		$grid->addColumn('expires', 'Expires');
		$grid->addColumn('locations', 'Locations');

		return $grid;
	}
}

interface IDomainGridFactory {

	/**
	 * @return IDomainGridFactory
	 */
	public function create();
}

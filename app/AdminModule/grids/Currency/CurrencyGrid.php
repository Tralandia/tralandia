<?php

namespace AdminModule\Grids;

use AdminModule\Components\AdminGridControl;

class CurrencyGrid extends AdminGridControl {

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
		$grid->addColumn('iso', 'Iso');
		$grid->addColumn('exchangeRate', 'Exchange rate');
		$grid->addColumn('rounding', 'Rounding');
		$grid->addColumn('searchInterval', 'Search interval');

		return $grid;
	}
}

interface ICurrencyGridFactory {

	/**
	 * @return ICurrencyGridFactory
	 */
	public function create();
}

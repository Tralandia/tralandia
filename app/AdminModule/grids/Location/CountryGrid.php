<?php

namespace AdminModule\Grids;

use AdminModule\Components\AdminGridControl;
use Nette\Utils\Paginator;

class CountryGrid extends AdminGridControl {

	public function __construct($repository) {
		$this->repository = $repository;
	}

	public function render() {

		$this->template->render();
	}

	public function createComponentGrid()
	{
		$grid = $this->getGrid();

		$grid->addColumn('name');
		$grid->addColumn('slug');
		// $grid->addColumn('continent');
		// $grid->addColumn('domain');
		// $grid->addColumn('currency');
		// $grid->addColumn('language');
		// $grid->addColumn('phonePrefix');

		return $grid;
	}

	/**
	 * @param $filter
	 * @param $order
	 * @param \Nette\Utils\Paginator $paginator
	 *
	 * @return mixed
	 */
	public function getDataSource($filter, $order, Paginator $paginator = NULL)
	{
		return $this->repository->findCountries();
	}
}

interface ICountryGridFactory {

	/**
	 * @return ILocationGridFactory
	 */
	public function create();
}

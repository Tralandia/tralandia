<?php

namespace AdminModule\Grids;

use AdminModule\Components\AdminGridControl;
use Nette\Utils\Paginator;

class LocalityGrid extends AdminGridControl {

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
		$grid->addColumn('parent', 'Country');

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
		return $this->repository->findLocalities();
	}
}

interface ILocalityGridFactory {

	/**
	 * @return ILocationGridFactory
	 */
	public function create();
}

<?php

namespace AdminModule\Grids;

use AdminModule\Components\AdminGridControl;
use DataSource\AmenityDataSource;

class AmenityGrid extends AdminGridControl {

	/**
	 * @var \DataSource\AmenityDataSource
	 */
	private $dataSource;


	public function __construct(AmenityDataSource $dataSource) {

		parent::__construct();
		$this->dataSource = $dataSource;
	}

	public function render() {

		$this->template->render();
	}

	public function createComponentGrid()
	{
		$grid = $this->getGrid();

		$grid->addColumn('type');
		$grid->addColumn('name', 'Name');
		$grid->addColumn('slug', 'Slug');
		$grid->addColumn('important', 'Important');
		$grid->addColumn('sorting', 'Sorting');

		$grid->setDataSourceCallback([$this->dataSource, 'getData']);

		return $grid;
	}

}

interface IAmenityGridFactory {

	/**
	 * @return IAmenityGridFactory
	 */
	public function create();
}

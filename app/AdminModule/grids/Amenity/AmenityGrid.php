<?php

namespace AdminModule\Grids;

use AdminModule\Components\AdminGridControl;
use DataSource\Amenity;

class AmenityGrid extends AdminGridControl {

	/**
	 * @var \DataSource\Amenity
	 */
	private $dataSource;


	public function __construct(Amenity $dataSource) {

		parent::__construct();
		$this->dataSource = $dataSource;
	}

	public function render() {

		$this->template->render();
	}

	public function createComponentGrid()
	{
		$grid = $this->getGrid();

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

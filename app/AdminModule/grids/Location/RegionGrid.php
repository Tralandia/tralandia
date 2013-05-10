<?php

namespace AdminModule\Grids;

use AdminModule\Components\AdminGridControl;
use DataSource\RegionDataSource;
use Nette\Utils\Paginator;

class RegionGrid extends AdminGridControl {

	/**
	 * @var \DataSource\RegionDataSource
	 */
	private $dataSource;


	public function __construct(RegionDataSource $dataSource) {

		parent::__construct();
		$this->dataSource = $dataSource;
	}

	public function render() {

		$this->template->render();
	}

	public function createComponentGrid()
	{
		$grid = $this->getGrid();

		$grid->addColumn('parent', 'Country');
		$grid->addColumn('name');
		$grid->addColumn('slug');
		$grid->addColumn('hasPolygons', 'Has polygons');

		$grid->setDataSourceCallback([$this->dataSource, 'getData']);

		return $grid;
	}


}

interface IRegionGridFactory {

	/**
	 * @return RegionGrid
	 */
	public function create();
}

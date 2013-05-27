<?php

namespace AdminModule\Grids;

use AdminModule\Components\AdminGridControl;
use DataSource\RentalDataSource;

class RentalGrid extends AdminGridControl {

	/**
	 * @var \DataSource\RentalDataSource
	 */
	private $dataSource;


	public function __construct(RentalDataSource $dataSource) {

		parent::__construct();
		$this->dataSource = $dataSource;
	}

	public function render() {

		$this->template->render();
	}

	public function createComponentGrid()
	{
		$grid = $this->getGrid();

		$grid->addColumn('id');
		$grid->addColumn('name');
		$grid->addColumn('email');
		$grid->addColumn('phone');

		$grid->setDataSourceCallback([$this->dataSource, 'getData']);


		return $grid;
	}

}

interface IRentalGridFactory {


	/**
	 * @return RentalGrid
	 */
	public function create();
}

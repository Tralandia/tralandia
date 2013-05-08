<?php

namespace AdminModule\Grids;

use AdminModule\Components\AdminGridControl;
use DataSource\Locality;
use Nette\Utils\Paginator;

class LocalityGrid extends AdminGridControl {

	/**
	 * @var \DataSource\Locality
	 */
	private $dataSource;


	public function __construct(Locality $dataSource) {

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

		$grid->setDataSourceCallback([$this->dataSource, 'getData']);

		return $grid;
	}

}

interface ILocalityGridFactory {

	/**
	 * @return ILocationGridFactory
	 */
	public function create();
}

<?php

namespace AdminModule\Grids;

use AdminModule\Components\AdminGridControl;
use DataSource\Country;
use Nette\Utils\Paginator;

class CountryGrid extends AdminGridControl {

	/**
	 * @var \DataSource\Country
	 */
	private $dataSource;


	public function __construct(Country $dataSource) {

		parent::__construct();
		$this->dataSource = $dataSource;
	}

	public function render() {

		$this->template->render();
	}

	public function createComponentGrid()
	{
		$grid = $this->getGrid();

		$grid->addColumn('parent', 'Continent');
		$grid->addColumn('name');
		$grid->addColumn('slug');
		$grid->addColumn('domain');

		$grid->setDataSourceCallback([$this->dataSource, 'getData']);

		return $grid;
	}

}

interface ICountryGridFactory {

	/**
	 * @return CountryGrid
	 */
	public function create();
}

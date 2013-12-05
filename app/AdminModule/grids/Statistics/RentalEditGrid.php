<?php

namespace AdminModule\Grids\Statistics;

use AdminModule\Components\AdminGridControl;
use Nette\ArrayHash;
use Nette\Utils\Paginator;

class RentalEditGrid extends AdminGridControl
{

	/**
	 * @var \Statistics\RentalEdit
	 */
	protected $dataSource;

	public function __construct(\Statistics\RentalEdit $dataSource) {
		$this->dataSource = $dataSource;
	}

	public function render() {
		$this->template->hideActions = false;
		$this->template->render();
	}

	public function createComponentGrid()
	{
		$this->showActions = false;

		$grid = $this->getGrid();
		$grid->setRowPrimaryKey('key');

		$grid->addColumn('key', 'Country');
		$grid->addColumn('today', 'Today');
		$grid->addColumn('yesterday', 'Yesterday');
		$grid->addColumn('thisWeek', 'This week');
		$grid->addColumn('lastWeek', 'Last week');
		$grid->addColumn('thisMonth', 'This month');
		$grid->addColumn('lastMonth', 'Last month');
		$grid->addColumn('total', 'Total');

		return $grid;
	}


	public function getDataSource($filter, $order, Paginator $paginator = NULL)
	{
		$data = $this->dataSource->getData($filter, $order, $paginator);
		return $data;
	}

}

interface IRentalEditGridFactory {

	/**
	 * @return RentalEditGrid
	 */
	public function create();
}

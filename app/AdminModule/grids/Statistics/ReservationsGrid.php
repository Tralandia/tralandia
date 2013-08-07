<?php

namespace AdminModule\Grids\Statistics;

use AdminModule\Components\AdminGridControl;
use Nette\Utils\Paginator;

class ReservationsGrid extends AdminGridControl {

	/**
	 * @var \Statistics\Reservations
	 */
	protected $dataSource;

	public function __construct(\Statistics\Reservations $dataSource) {
		$this->dataSource = $dataSource;
	}

	public function render() {
		$this->template->render();
	}

	public function createComponentGrid()
	{
		$this->showActions = FALSE;

		$grid = $this->getGrid();
		$grid->setRowPrimaryKey('iso');

		$grid->addColumn('iso', 'Country');
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

interface IReservationsGridFactory {

	/**
	 * @return ReservationsGrid
	 */
	public function create();
}

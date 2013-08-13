<?php

namespace AdminModule\Grids\Statistics;

use AdminModule\Components\AdminGridControl;
use Nette\ArrayHash;
use Nette\Utils\Paginator;

class RegistrationsGrid extends AdminGridControl {

	/**
	 * @var \Statistics\Registrations
	 */
	protected $dataSource;

	public function __construct(\Statistics\Registrations $dataSource) {
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

interface IRegistrationsGridFactory {

	/**
	 * @return IPhraseTypeGridFactory
	 */
	public function create();
}

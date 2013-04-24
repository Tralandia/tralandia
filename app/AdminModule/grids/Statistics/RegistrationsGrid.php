<?php

namespace AdminModule\Grids\Statistics;

use AdminModule\Components\AdminGridControl;
use Nette\ArrayHash;
use Nette\Utils\Paginator;

class RegistrationsGrid extends AdminGridControl {

	protected $dataSource;

	public function __construct($dataSource) {
		$this->dataSource = $dataSource;
	}

	public function render() {

		$this->template->render();
	}

	public function createComponentGrid()
	{
		$grid = $this->getGrid();
		$grid->setRowPrimaryKey('key');

		$grid->addColumn('total', 'Total');
		$grid->addColumn('today', 'Today');
		$grid->addColumn('yesterday', 'Yesterday');
		$grid->addColumn('this_week', 'This week');
		$grid->addColumn('last_week', 'Last week');
		$grid->addColumn('this_month', 'This month');
		$grid->addColumn('last_month', 'Last month');

		return $grid;
	}


	public function getDataSource($filter, $order, Paginator $paginator = NULL)
	{
		$data = $this->dataSource->getData($filter, $order, $paginator);
		d($data);
		return $data;
	}

}

interface IRegistrationsGridFactory {

	/**
	 * @return IPhraseTypeGridFactory
	 */
	public function create();
}

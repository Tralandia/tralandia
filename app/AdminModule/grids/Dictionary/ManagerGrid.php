<?php

namespace AdminModule\Grids\Dictionary;

use AdminModule\Components\AdminGridControl;
use DataSource\DictionaryManagerDataSource;

class ManagerGrid extends AdminGridControl {

	/**
	 * @var \DataSource\DictionaryManagerDataSource
	 */
	private $dataSource;


	public function __construct(DictionaryManagerDataSource $dataSource) {
		$this->dataSource = $dataSource;
		parent::__construct();
	}

	public function render() {

		$this->template->datagridClass = 'dictionary-manager';
		$this->template->render();
	}

	public function createComponentGrid()
	{
		$grid = $this->getGrid();

		$grid->addColumn('name', 'ISO');
		//$grid->addColumn('lastTranslation', 'Last Translation');
		$grid->addColumn('toTranslate', 'To translate');
		$grid->addColumn('toCheck', 'To check');
		$grid->addColumn('priceToPay', 'To pay');

		$grid->setDataSourceCallback([$this->dataSource, 'getData']);

		return $grid;
	}

}

interface IManagerGridFactory {

	/**
	 * @return IManagerGridFactory
	 */
	public function create();
}

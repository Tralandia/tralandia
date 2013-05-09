<?php

namespace AdminModule\Grids\Dictionary;

use AdminModule\Components\AdminGridControl;
use DataSource\DictionaryManager;
use Doctrine\ORM\EntityManager;

class ManagerGrid extends AdminGridControl {

	/**
	 * @var \DataSource\DictionaryManager
	 */
	private $dataSource;


	public function __construct(DictionaryManager $dataSource) {
		$this->dataSource = $dataSource;
	}

	public function render() {

		$this->template->render();
	}

	public function createComponentGrid()
	{
		$grid = $this->getGrid();

		$grid->addColumn('name', 'Name');
		$grid->addColumn('lastTranslation', 'Last Tran.');
		$grid->addColumn('toTranslate', 'To Tran.');
		$grid->addColumn('toCheck', 'To check.');
		$grid->addColumn('translator', 'Translator');

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

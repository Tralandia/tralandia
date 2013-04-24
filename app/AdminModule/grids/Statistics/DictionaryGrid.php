<?php

namespace AdminModule\Grids\Statistics;

use AdminModule\Components\AdminGridControl;
use Nette\ArrayHash;
use Nette\Utils\Paginator;

class DictionaryGrid extends AdminGridControl {

	/** 
	 * @var \Statistics\Dictionary 
	 */
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

		$grid->addColumn('language', 'Jazyk');
		$grid->addColumn('count', 'pocet');

		return $grid;
	}


	public function getDataSource($filter, $order, Paginator $paginator = NULL)
	{
		$data = $this->dataSource->getData($filter, $order, $paginator);
		return $data;
	}




}

interface IDictionaryGridFactory {

	/**
	 * @return IPhraseTypeGridFactory
	 */
	public function create();
}

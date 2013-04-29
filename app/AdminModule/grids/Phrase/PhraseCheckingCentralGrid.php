<?php

namespace AdminModule\Grids;

use AdminModule\Components\AdminGridControl;

class PhraseCheckingCentralGrid extends AdminGridControl {

	public function __construct($repository) {
		$this->repository = $repository;
	}

	public function render() {

		$this->template->render();
	}

	public function createComponentGrid()
	{
		$grid = $this->getGrid();

		$grid->addColumn('entityName', 'Entity name');
		$grid->addColumn('entityAttribute', 'Entity attribute');
		$grid->addColumn('required');

		return $grid;
	}

	/**
	 * @param $filter
	 * @param $order
	 * @param \Nette\Utils\Paginator $paginator
	 *
	 * @return mixed
	 */
	public function getDataSource($filter, $order, \Nette\Utils\Paginator $paginator = NULL)
	{
		return $this->repository->findMissingCentralTranslations();
	}

}

interface IPhraseCheckingCentralGridFactory {

	/**
	 * @return IPhraseCheckingCentralGridFactory
	 */
	public function create();
}

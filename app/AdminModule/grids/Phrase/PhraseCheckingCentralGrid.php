<?php

namespace AdminModule\Grids;

use AdminModule\Components\AdminGridControl;

class PhraseCheckingCentralGrid extends AdminGridControl {

	/**
	 * @var \Dictionary\FindOutdatedTranslations
	 */
	protected $findOutdatedTranslations;

	public function __construct($repository, \Dictionary\FindOutdatedTranslations $findOutdatedTranslations) {
		$this->repository = $repository;
		$this->findOutdatedTranslations = $findOutdatedTranslations;
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
		$limit = $paginator->itemsPerPage;
		$offset = ($paginator->page - 1) * $paginator->itemsPerPage;
		$data = $this->findOutdatedTranslations->getWaitingForCentral(NULL, $limit, $offset);

		return $data;
	}

}

interface IPhraseCheckingCentralGridFactory {

	/**
	 * @return IPhraseCheckingCentralGridFactory
	 */
	public function create();
}

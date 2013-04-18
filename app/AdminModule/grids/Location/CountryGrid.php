<?php

namespace AdminModule\Grids;

use AdminModule\Components\AdminGridControl;
use Nette\Utils\Paginator;

class CountryGrid extends AdminGridControl {

	public function __construct($repository, \Extras\Translator $translator, \Environment\Collator $collator) {
		$this->repository = $repository;

		parent::__construct($translator, $collator);
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

		return $grid;
	}

	/**
	 * @param $filter
	 * @param $order
	 * @param \Nette\Utils\Paginator $paginator
	 *
	 * @return mixed
	 */
	public function getDataSource($filter, $order, Paginator $paginator = NULL)
	{
		return $this->getTranslatedAndOrderedBy($this->repository->findCountries(), 'parent->name');
	}
}

interface ICountryGridFactory {

	/**
	 * @return ILocationGridFactory
	 */
	public function create();
}

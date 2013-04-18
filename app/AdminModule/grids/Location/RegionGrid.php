<?php

namespace AdminModule\Grids;

use AdminModule\Components\AdminGridControl;
use Nette\Utils\Paginator;

class RegionGrid extends AdminGridControl {

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

		$grid->addColumn('parent', 'Country');
		$grid->addColumn('name');
		$grid->addColumn('slug');
		$grid->addColumn('hasPolygons', 'Has polygons');


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
		return $this->getTranslatedAndOrderedBy($this->repository->findRegions(), 'parent->name');
	}
}

interface IRegionGridFactory {

	/**
	 * @return ILocationGridFactory
	 */
	public function create();
}

<?php

namespace AdminModule\Grids;

use AdminModule\Components\AdminGridControl;

class AmenityGrid extends AdminGridControl {

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

		$grid->addColumn('name', 'Name');
		$grid->addColumn('slug', 'Slug');
		$grid->addColumn('important', 'Important');
		$grid->addColumn('sorting', 'Sorting');

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
		$builder = $this->repository->getDataSource();
		$query = $builder->getQuery();
		$orderedAmenities = $this->getTranslatedAndOrderedBy($query->getResult(), 'name');

		$importants = [];
		$notImportants = [];
		foreach ($orderedAmenities as $key => $entity) {
			if ($entity->important) {
				$importants[$key] = $entity;
			} else {
				$notImportants[$key] = $entity;
			}
		}

		$amenities = array_merge($importants, $notImportants);

		return $amenities;
	}
}

interface IAmenityGridFactory {

	/**
	 * @return IAmenityGridFactory
	 */
	public function create();
}

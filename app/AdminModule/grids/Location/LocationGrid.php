<?php

namespace AdminModule\Grids;

use Nette, TwiGrid, AdminModule\Components\Grid;

class LocationGrid extends Grid {

	const TYPE_CONTINENT = 'continent';
	const TYPE_COUNTRY = 'country';
	const TYPE_REGION = 'region';
	const TYPE_LOCALITY = 'locality';

	protected $type = self::TYPE_CONTINENT;
	protected $defaultCountry = 56;

	public function render() {
		foreach ($this->columns as $name => $options) {
			$this->grid->addColumn($name, $options['label']);
		}

		$this->type = self::TYPE_CONTINENT;

		$this->grid->setTemplateFile(__DIR__ . '/grid.latte');
		$this->grid->setPrimaryKey('id');
		$this->grid->setDataLoader($this->dataLoader);
		$this->grid->setRecordValueGetter($this->recordValueGetter);
		$this->grid->setTimelineBehavior(true);
		
		$this->grid->addRowAction('edit', 'Edit', $this->editRecord);
		$this->grid->addRowAction('delete', 'Smazat', $this->deleteRecord, 'Opravdu chcete smazat tento záznam?');

		return $this->grid;
	}

	public function renderCountries() {
		$this->render();
		$this->type = self::TYPE_COUNTRY;
		return $this->grid;
	}

	public function renderRegions() {
		$this->render();
		$this->type = self::TYPE_REGION;
		$this->grid->setFilterContainerFactory($this->createFilterContainer);
		$this->grid->setDefaultFilters(array(
			'country' => $this->defaultCountry
		));
		return $this->grid;
	}

	public function renderLocalities() {
		$this->render();
		$this->type = self::TYPE_LOCALITY;
		$this->grid->setFilterContainerFactory($this->createFilterContainer);
		$this->grid->setDefaultFilters(array(
			'country' => $this->defaultCountry
		));
		return $this->grid;
	}

	function createFilterContainer() {
		$container = new Nette\Forms\Container;
		$container->addSelect('country', 'Country', $this->repository->getCountriesForSelect())
			->setPrompt('---');
		return $container;
	}

	function dataLoader(TwiGrid\DataGrid $grid, array $columns, array $orderBy, array $filters, $page) {
		$builder = $this->repository->getDefaultDataSource($this->type);
		$builder->orderBy('e.' . $this->orderBy, $this->sort);

		if (isset($filters['country'])) {
			$builder->andWhere('e.parent = :parent')->setParameter('parent', $filters['country']);
		}

		return $builder->getQuery()
			->setFirstResult(($page - 1) * $this->itemsPerPage)
			->setMaxResults($this->itemsPerPage)
			->getResult();
	}
}
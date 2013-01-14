<?php

namespace AdminModule\Components;

use Nette, TwiGrid, Doctrine, Extras, Entity;

class Grid extends Nette\Object {

	protected $grid;
	protected $presenter;
	protected $repository;

	protected $columns = array();
	protected $itemsPerPage = 20;
	protected $orderBy = 'id';
	protected $sort = 'ASC';

	public function __construct(array $parameters, TwiGrid\DataGrid $grid, Nette\Application\IPresenter $presenter, Doctrine\ORM\EntityRepository $repository) {
		$this->grid = $grid;
		$this->presenter = $presenter;
		$this->repository = $repository;

		isset($parameters['itemsPerPage']) && $this->itemsPerPage = $parameters['itemsPerPage'];
		isset($parameters['orderBy']) && $this->orderBy = $parameters['orderBy'];
		isset($parameters['columns']) && $this->columns = $parameters['columns'];

		if (empty($this->columns)) {
			//throw new Nette\InvalidStateException("Neboli nastavene ziadne stlpce v konfigu");
		}
	}

	public function render() {

		$this->grid->setTemplateFile(__DIR__ . '/grid.latte');

		foreach ($this->columns as $name => $options) {
			$this->grid->addColumn($name, $options['label']);
		}


		$this->grid->setPrimaryKey('id');
		//$this->grid->setFilterContainerFactory( $this->createFilterContainer );
		$this->grid->setDataLoader($this->dataLoader);
		$this->grid->setRecordValueGetter($this->recordValueGetter);
		$this->grid->setTimelineBehavior(true);

		//$this->grid->setInlineEditing($this->createInlineEditContainer, $this->processInlineEditForm);
		$this->grid->addRowAction('edit', 'Edit', $this->editRecord);
		$this->grid->addRowAction('delete', 'Smazat', $this->deleteRecord, 'Opravdu chcete smazat tento záznam?');

		//$this->grid->setDefaultFilters(array(
		//	'birthday' => $this->loadMinMaxBirthday(),
		//));

		return $this->grid;
	}

	function dataLoader(TwiGrid\DataGrid $grid, array $columns, array $orderBy, array $filters, $page) {
		$builder = $this->repository->getDataSource();
		$builder->orderBy('e.' . $this->orderBy, $this->sort);


		$query = $builder->getQuery();
		$query->setFirstResult(($page - 1) * $this->itemsPerPage);
		$query->setMaxResults($this->itemsPerPage);
		return $query->getResult();
	}

	function recordValueGetter(Extras\Models\Entity\IEntity $record, $column) {
		if ($record->$column instanceof Entity\Phrase\Phrase) {
			return $this->presenter->translate($record->$column);
		}
		return $record->$column;
	}

	function editRecord($id) {
		$this->presenter->redirect('edit', $id);
	}

	function deleteRecord($id) {
		$this->presenter->flashMessage( "Požadavek na smazání záznamu s ID '$id'.", 'warning' );
		!$this->presenter->isAjax() && $this->presenter->redirect('this');
	}
}
<?php

namespace AdminModule;

use Nette, Extras, Service, Entity, TwiGrid;

class AdminPresenter extends BasePresenter {

	protected $settings;

	public function getEntityName() {
		return '\\Entity\\' . ucfirst($this->getConfigName());
	}

	public function getServiceName() {
		return '\\Service\\' . ucfirst($this->getConfigName()) . 'Service';
	}

	public function getConfigName() {
		$parts = explode(':', $this->name);
		return strtolower(end($parts));
	}

	public function startup() {
		parent::startup();
		
		$this->settings = $this->getService('presenter.' . $this->getConfigName() . '.settings');
		$this->template->settings = $this->settings;

	}

	public function actionList() {

	}

	public function actionAdd() {
		$repo = $this->context->model->getRepository($this->getEntityName());
		$entity = $repo->createNew();
		$this->context->model->persist($entity);
		$this->template->form = $this->getForm($this->getConfigName(), $entity);
		$this->settings->name = 'novééé';
	}

	public function actionEdit($id) {
		$repo = $this->context->model->getRepository($this->getEntityName());
		$entity = $repo->find($id);
		$this->template->form = $this->getForm($this->getConfigName(), $entity);
		$this->settings->name = $entity->id . ' - dynamika';
	}

	public function getForm($name, $entity) {
		$model = $this->getService('model');
		$form = $this->getService("presenter.$name.form")->create($entity);
		$form->onSuccess[] = function($form) use ($model) {
			$model->flush();
		};
		$this->addComponent($form, $name);
		return $form;
	}

	protected function createComponentDataGrid() {
		$grid = $this->context->createDataGrid();

		$grid->setTemplateFile(__DIR__ . '/../templates/datagrid.latte');

		$grid->addColumn('name', 'Name')->setSortable();
		$grid->addColumn('iso', 'ISO')->setSortable();
		$grid->addColumn('exchangeRate', 'Exchange Rate');
		$grid->addColumn('rounding', 'Rounding')->setSortable();


		$grid->setPrimaryKey('id');
		//$grid->setFilterContainerFactory( $this->createFilterContainer );
		$grid->setDataLoader($this->dataLoader);
		$grid->setRecordValueGetter($this->recordValueGetter);

		//$grid->setInlineEditing($this->createInlineEditContainer, $this->processInlineEditForm);
		//$grid->addRowAction('delete', 'Smazat', $this->deleteRecord, 'Opravdu chcete smazat tento záznam?');

		//$grid->setDefaultFilters(array(
		//	'birthday' => $this->loadMinMaxBirthday(),
		//));


		return $grid;
	}

	function dataLoader(TwiGrid\DataGrid $grid, array $columns, array $orderBy, array $filters, $page) {
		$builder = $this->context->model->getRepository($this->getEntityName())->getDataSource();

		$query = $builder->getQuery();
		$query->setFirstResult($page);
		$query->setMaxResults(20);
		return $query->getResult();
	}

	function recordValueGetter(Extras\Models\Entity\IEntity $record, $column) {
		if ($record->$column instanceof Entity\Phrase\Phrase) {
			return $this->translate($record->$column);
		}
		return $record->$column;
	}
}
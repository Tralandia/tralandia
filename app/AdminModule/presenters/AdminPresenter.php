<?php

namespace AdminModule;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html;

class AdminPresenter extends BasePresenter {
	
	private $serviceSettings;
	
	private $service;
	
	public function startup() {
		parent::startup();
		
		$settings = $this->getService('settings');
		$this->template->settings = $settings;
		$this->service = new $settings->serviceClass;
	}
	

	public function renderList() {
	}
	
	public function renderAdd() {
		$form = $this->getComponent('form');
	}
	
	public function renderEdit($id = 0) {
		$form = $this->getComponent('form');
		$row = $this->service->get($id);

		if (!$row) {
			throw new NA\BadRequestException('Record not found');
		}
		if (!$form->isSubmitted()) {
			$form->setDefaults($row);
		}

		$this->template->record = $row;
		$this->template->form = $form;
	}
	
	protected function createComponentForm($name) {
		$form = new \Tra\Forms\Form($this, $name);
		$this->service->prepareForm($form);
		
		$form->ajax(false);
		$form->addSubmit('save', 'Save');
		$form->onSuccess[] = callback($this, 'onSave');
		
		return $form;
	}
	
	public function onSave(\Tra\Forms\Form $form) {
		$id = (int)$this->getParam()->id;
		$values = $form->getPrepareValues($this->service);		
		
		if ($id > 0) {
			// EDIT
			$this->service->update($values);
		} else {
			// ADD
			$this->service->create($values);
		}	
    }
	
	
	protected function createComponentGrid($name) {
		$grid = new \EditableDataGrid\DataGrid;

		$dataSource = new \DataGrid\DataSources\Doctrine\LalaQueryBuilder($this->service->getDataSource());
		$dataSource->setMapping(array(
			'id' => 'e.id',
			'country' => 'e.country.iso',
			'countryID' => 'e.country.id',
			'user' => 'e.user.login',
			'nameUrl' => 'e.nameUrl',
			'status' => 'e.status',
			'created' => 'e.created',
			'total' => 'total'
		));

		$grid->setDataSource($dataSource);
		//$this->service->prepareDataGrid($grid);
		
		$grid->addColumn('country', 'ISO');
		$grid->addColumn('user', 'User');
		$grid->addColumn('nameUrl', 4242);
		$grid->addColumn('status', 'Status');
		$grid->addColumn('total', 'Total');
		$grid->addDateColumn('created', 'Date', '%d.%m.%Y')->addDefaultSorting('desc');
		
		/*
		$grid->setTranslator(Environment::getService('translator'));
		$grid->itemsPerPage = 20;
		$grid->multiOrder = FALSE;
		$grid->displayedItems = array(20, 50, 100);

		$dataSource = new \DataGrid\DataSources\Doctrine\QueryBuilder($this->em->getRepository('Rental')->getDataSource());
		$dataSource->setMapping(array(
			'id' => 'r.id',
			'country' => 'c.iso',
			'user' => 'u.login',
			'nameUrl' => 'r.nameUrl',
			'status' => 'r.status',
			'created' => 'r.created'
		));

		$grid->setDataSource($dataSource);
		$grid->addColumn('country', 'ISO');
		$grid->addColumn('user', 'User');
		$grid->addColumn('nameUrl', 4242);
		$grid->addColumn('status', 'Status');
		$grid->addDateColumn('created', 'Date', '%d.%m.%Y')->addDefaultSorting('desc');
		*/
		$grid->addActionColumn('Actions');
		$grid->addAction('Edit', 'edit', Html::el('span')->class('icon edit')->setText('Edit') , false);
		$grid->addAction('Delete', 'delete', Html::el('span')->class('icon delete')->setText('Delete'), false);

		/*
		//$grid->setEditForm($this->getComponent('gridForm'));
		//debuge($this->getComponent('gridForm'));
		$grid->setEditForm($this->getComponent('gridForm'));
		//$grid->addEditableField('country');
		$grid->setContainer('Rental');
		$grid->addEditableField('user');
		$grid->addEditableField('nameUrl');
		//$grid->addEditableField('status');
		$grid->onDataReceived[] = array($this, 'onDataRecieved');
		$grid->onInvalidDataReceived[] = array($this, 'onDataRecieved');
		$grid->onInvalidDataReceived[] = array($this, 'onInvalidDataRecieved');
		*/
		
		return $grid;
	}
	
	function createComponentGridForm($name) {
		
		$grid = new \Tra\Forms\Grid($this, $name);
		
		//$this->service->prepareGridForm($grid);
		
		
		return $grid;
	}
}

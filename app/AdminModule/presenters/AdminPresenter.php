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
		
		$this->serviceSettings = \Nette\ArrayHash::from(array(
			'name' => ucfirst($this->getParams()->form) . ' ' . ucfirst($this->action),
			'class' => '\\Tra\\Services\\' . ucfirst($this->getParams()->form)
		));
	}
	

	public function renderList() {
		$this->template->settings = $this->serviceSettings;
	}
	
	public function renderEdit($id = 0) {
		
		
		$this->template->settings = $this->serviceSettings;
	}
	
	protected function createComponentGrid($name) {
		$grid = new \EditableDataGrid\DataGrid;
		
		$this->service = new $this->serviceSettings->class;
		$this->service->prepareDataGrid($grid);
		
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

		return $grid;
	}
	
	function createComponentGridForm($name) {
		
		$grid = new \Tra\Forms\Grid($this, $name);
		
		$this->service = new $this->serviceSettings->class;
		$this->service->prepareGridForm($grid);
		
		
		return $grid;
	}
}

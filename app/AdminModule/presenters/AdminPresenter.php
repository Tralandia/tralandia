<?php

namespace AdminModule;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html;

class AdminPresenter extends BasePresenter {
	
	private $settings;
	
	private $service;
	
	public function startup() {
		parent::startup();
		
		$this->settings = $this->getService('settings');
		$this->template->settings = $this->settings;
		$this->service = new $this->settings->serviceClass;
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
		$form = $this->getComponent('gridForm');
		$grid = new \EditableDataGrid\DataGrid;
		//$grid->itemsPerPage = 3;
		
		$grid->setEditForm($form);
		$grid->setContainer($this->service->getMainEntity());	
		$grid->onDataReceived[] = array($form, 'onDataRecieved');
		$grid->onInvalidDataRecieved[] = array($form, 'onInvalidDataRecieved');
		
		$mapper = array(); $editable = false;
		foreach ($this->settings->params->grid->columns as $alias => $column) {
			$mapper[$alias] = $column->mapper;
			
			if (!isset($column->draw) || (isset($column->draw) && $column->draw == true)) {
				$type = isset($column->type) ? $column->type : 'text';				
				$property = substr($column->mapper, strrpos($column->mapper, '.')+1);
				
				if ($controlAnnotation = $this->service->getReflector()->getAnnotation('Rental', $property, 'Column')) {
					$type = $controlAnnotation->type;
				}

				if ($controlAnnotation = $this->service->getReflector()->getAnnotation('Rental', $property, 'UIControl')) {
					$type = $controlAnnotation->type;
				}
				
				switch ($type) {
					case 'datetime':	
					case 'date':	$grid->addDateColumn($alias, $column->label, '%d.%m.%Y'); break; // TODO: poriesit formatovanie datumov
					default:		$grid->addColumn($alias, $column->label);
				}
				
				if (isset($column->callback)) {
					$column->callback->class == '%this%' ? $column->callback->class = $this : $column->callback->class;
					
					$grid->getComponent($alias)->formatCallback[] = new \DataGrid\Callback(
						$column->callback->class,
						$column->callback->method,
						isset($column->callback->params) ? $column->callback->params : null
					);
				}
			}
			if (isset($column->editable) && $column->editable == true) {
				$editable = true;
				$grid->addEditableField($alias);
			}
		}
		
		$dataSource = new \DataGrid\DataSources\Doctrine\LalaQueryBuilder($this->service->getDataSource());
		$dataSource->setMapping($mapper);
		$grid->setDataSource($dataSource);	
		$grid->addActionColumn('Actions');
		
		
		$grid->addAction('Edit', 'edit', Html::el('span')->class('icon edit')->setText('Edit') , false);
		$grid->addAction('Delete', 'delete', Html::el('span')->class('icon delete')->setText('Delete'), false);
		
		return $grid;
	}
	
	public function createComponentGridForm($name) {
		$grid = new \Tra\Forms\Grid($this, $name);
		return $grid;
	}
	
	public function pattern($value, $row, $params = null) {
		return preg_replace_callback('/%([\w]*)%/', function($matches) use ($row) {
			return isset($row[$matches[1]]) ? $row[$matches[1]] : $matches[0];
		}, $params->pattern);
	}

	public function nieco($value, $row, $params = null) {
		//debug($value, $row, $params);
		return $value;
	}	
}

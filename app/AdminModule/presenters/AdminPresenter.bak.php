<?php

namespace AdminModule;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Extras\Models\Reflector;

class AdminPresenter2 extends BasePresenter {
	
	private $settings;
	private $serviceName;
	private $serviceListName;
	private $reflector;
	
	public function startup() {
		parent::startup();
		
		$this->settings = $this->getService('settings');
		$this->template->settings = $this->settings;
		$this->serviceName = $this->settings->serviceClass;
		$this->serviceListName = $this->settings->serviceListClass;
		$this->reflector = new Reflector($this->serviceName);
	}
	
	public function getMainServiceName() {
		return $this->serviceName;
	}

	public function renderList() {
		
	}
	
	public function renderAdd() {
		$form = $this->getComponent('form');
	}
	
	public function actionEdit($id = 0) {
		$form = $this->getComponent('form');
		$service = $this->serviceName;
		$service = $service::get($id);

		//TODO: naslo zaznam? toto treba osetrit lebo servica nehlasi nenajdeny zaznam
		// ale hlasi @david
		// if (!$service) {
		// 	throw new NA\BadRequestException('Record not found');
		// }

		$service->setCurrentMask($this->reflector->getMask());
		if (!$form->isSubmitted()) {
			$data = $service->getDataByMask();
			$this->reflector->getContainer($form)
				->setDefaults($data);
		}

		$this->template->record = $service;
		$this->template->form = $form;
	}
	
	protected function createComponentForm($name) {
		$form = new \Tra\Forms\Form($this, $name);
		$this->reflector->extend($form);
		$form->ajax(false);
		$form->addSubmit('save', 'Save');
		$form->onSuccess[] = callback($this, 'onSave');
		return $form;
	}
	
	public function onSave(\Tra\Forms\Form $form) {
		$id = $this->getParam('id');
		$values = $this->reflector->getPrepareValues($form);


		$service = $this->serviceName;
		$service = $service::get($id);

		if ($id) {
			// EDIT
			$service->updateFormData($values);
		} else {
			// ADD
			$service->create($values);
		}
    }
	
	protected function createComponentGrid($name) {
		$mainEntityName = $this->reflector->getMainEntityName();
		//$form = $this->getComponent('gridForm');
		$grid = new \DataGrid\DataGrid;
		//$grid->itemsPerPage = 3;

		//$grid->setEditForm($form);
		//$grid->setContainer($this->reflector->getContainerName());	
		//$grid->onDataReceived[] = array($form, 'onDataRecieved');
		//$grid->onInvalidDataRecieved[] = array($form, 'onInvalidDataRecieved');
	
		$mapper = array(); $editable = false;
		foreach ($this->settings->params->grid->columns as $alias => $column) {
			$mapper[$alias] = $column->mapper;
			
			if (!isset($column->draw) || (isset($column->draw) && $column->draw == true)) {
				$type = isset($column->type) ? $column->type : 'text';				
				$property = substr($column->mapper, strrpos($column->mapper, '.')+1);

				if ($controlAnnotation = $this->reflector->getAnnotation($mainEntityName, $property, Reflector::COLUMN)) {
					$type = $controlAnnotation->type;
				}

				if ($controlAnnotation = $this->reflector->getAnnotation($mainEntityName, $property, Reflector::UI_CONTROL)) {
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
				//$grid->addEditableField($alias);
			}
		}

		$list = $this->serviceListName;
		$list = $list::getAll();
		$dataSource = new \DataGrid\DataSources\Doctrine\LalaQueryBuilder(
			$list->getDataSource()
		);

		$dataSource->setMapping($mapper);
		$grid->setDataSource($dataSource);	
		$grid->addActionColumn('Actions');
		
		$grid->addAction('Edit', 'edit', Html::el('span')->class('icon edit')->setText('Edit') , false);
		$grid->addAction('Delete', 'delete', Html::el('span')->class('icon delete')->setText('Delete'), false);

		return $grid;
	}
	
	public function createComponentGridForm($name) {
		$form = new \Tra\Forms\Form($this, $name);
		$this->reflector->extend($form);

		$form->ajax(false);
		$form->addSubmit('save', 'Save');
		$form->onSuccess[] = callback($this, 'onGridSave');
		return $form;
	}

	public function onGridSave($form) {
		debug($form->getValues());
    }
	
	public function pattern($value, $row, $params = null) {
		//debug("odpoved=" . $this->user->isAllowed($row->getEntity(), 'show'));
		
		return preg_replace_callback('/%([\w]*)%/', function($matches) use ($row) {
			return isset($row[$matches[1]]) ? $row[$matches[1]] : $matches[0];
		}, $params->pattern);
	}

	public function translateColumn($value, $row, $key) {
		return $this->translate($row->getEntity()->$key->id);
	}
}

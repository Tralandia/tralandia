<?php

namespace AdminModule;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Extras\Models\Reflector;

class AdminPresenter extends BasePresenter {
	
	private $settings;
	private $service;
	private $serviceName;
	private $serviceListName;
	private $reflector;
	private $formMask;
	
	public function startup() {
		parent::startup();
		
		$this->settings = $this->getService('settings');
		$this->template->settings = $this->settings;
		$this->serviceName = $this->settings->serviceClass;
		$this->serviceListName = $this->settings->serviceListClass;
		$this->reflector = new Reflector($this->settings);
	}
	
	public function getMainServiceName() {
		return $this->serviceName;
	}

	public function renderList() {
		
	}
	
	public function actionAdd() {
		$form = $this->getComponent('form');
		// TODO: instancia uplne noveho zaznamu
		//$this->service = new Service;
	}

	public function actionEdit($id = 0) {
		$service = $this->serviceName;
		$this->service = $service::get($id);

		$this->formMask = $this->reflector->getFormMask();

		$this->service->setCurrentMask($this->formMask);
	}
	
	public function renderEdit($id = 0) {
		$form = $this->getComponent('form');
		//TODO: naslo zaznam? toto treba osetrit lebo servica nehlasi nenajdeny zaznam
		// ale hlasi @david
		// if (!$this->service) {
		// 	throw new NA\BadRequestException('Record not found');
		// }

		if (!$form->isSubmitted()) {
			$data = $this->service->getDefaultsData($this->formMask);
			$this->reflector->getContainer($form)->setDefaults($data);
		}

		$this->template->record = $this->service;
		$this->template->form = $form;
	}
	
	protected function createComponentForm($name) {
		$form = new \AdminModule\Forms\AdminForm($this, $name, $this->reflector, $this->service);

		return $form;
	}
		
	protected function createComponentGrid($name) {
		$mainEntityName = $this->reflector->getMainEntityName();
		$grid = new \DataGrid\DataGrid;
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

	public function pattern($value, $row, $params = null) {
		return preg_replace_callback('/%([\w]*)%/', function($matches) use ($row) {
			return isset($row[$matches[1]]) ? $row[$matches[1]] : $matches[0];
		}, $params->pattern);
	}

	public function translateColumn($value, $row, $key) {
		return $this->translate($row->getEntity()->$key->id);
	}
}

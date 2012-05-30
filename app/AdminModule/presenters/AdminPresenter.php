<?php

namespace AdminModule;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Tra\Utils\Arrays,
	Nette\ArrayHash,
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
		$this->reflector = new Reflector($this->settings, $this);
	}
	
	public function getMainServiceName() {
		return $this->serviceName;
	}

	public function renderList() {
		$this->template->showAddNewButton = $this->settings->params->addNewButton;
	}
	
	public function actionAdd() {
		$service = $this->serviceName;
		$this->service = $service::get();
		$this->formMask = $this->reflector->getFormMask($this->service, $this->settings->params->addForm);
	}

	public function renderAdd() {		
		$form = $this->getComponent('form');

		$this->service->setDefaultsFormData($this->reflector->getContainer($form), $this->formMask);

		$this->template->record = $this->service;
		$this->template->form = $form;
		$this->template->setFile(APP_DIR . '/AdminModule/templates/Admin/edit.latte');
		$this->setRenderMode();
	}

	public function actionEdit($id = 0) {
		$service = $this->serviceName;
		$this->service = $service::get($id);
		$this->formMask = $this->reflector->getFormMask($this->service, $this->settings->params->form);

	}
	
	public function renderEdit($id = 0) {
		$form = $this->getComponent('form');

		$this->service->setDefaultsFormData($this->reflector->getContainer($form), $this->formMask);

		$this->template->record = $this->service;
		$this->template->form = $form;
		$this->template->created = $this->service->created;
		$this->template->updated = $this->service->updated;
		$this->setRenderMode();
	}

	public function setRenderMode() {
		if(isset($this->params['display']) && $this->params['display'] == 'modal') {
			$this->formMask->form->addClass .= ' ajax';
			$this->setLayout('modalLayout');
			$this->template->display = 'modal';
		}
	}
	
	protected function createComponentForm($name) {
		$form = new \AdminModule\Forms\AdminForm($this, $name);

		$this->reflector->extend($form, $this->formMask);

		//$this->addAdvancedFileManager('upload', 'File manager');
		$form->onSuccess[] = callback($this, 'formOnSuccess');

		return $form;
	}

	public function formOnSuccess(\AdminModule\Forms\AdminForm $form) {
		$id = $this->presenter->getParam('id');
		$values = $this->reflector->getPrepareValues($form);

		if ($this->service->id) {
			// EDIT
			$this->service->updateFormData($this->formMask, $values);
		} else {
			// ADD
			$this->service->create($this->formMask, $values);
			$this->redirect('edit', array('id' => $this->service->id));
		}

		$this->flashMessage('A je to!');
		if($this->getPresenter()->isAjax()) {
			$this->getPresenter()->payload->invalidateParent = true;
		}
	}



/*	public function handleImageUpload(\Nette\Http\FileUpload $file) {
		//debug($file);
		$this->invalidateControl();
		try {
			$file = \Service\Medium\Medium::createFromFile($file->getTemporaryFile(), $file->getName());
			$this->getPresenter()->getPayload()->success = true;
		} catch (Exception $e) {
			$this->getPresenter()->getPayload()->error = $e->getMessage();
		}
	}


	public function loadState(array $params) {
		$globals = $this->getPresenter()->getRequest()->getParameters();
		if (isset($globals['qqfile'])) {
			list($none, $signal) = $this->getPresenter()->getSignal();
			$handle = 'handle' . ucfirst($signal);
			
			if ($this->getReflection()->hasMethod($handle)) {
				$parameters = $this->getReflection()->getMethod($handle)->getParameters();
				$file = new \qqUploadedFileXhr;
				$file = $file->save();
				$file = new \Nette\Http\FileUpload($file);
				$params[$parameters[0]->getName()] = $file;
			}
		}
		parent::loadState($params);
	}
*/		
	protected function createComponentGrid($name) {
		$mainEntityName = $this->reflector->getMainEntityName();
		$grid = new \DataGrid\DataGrid;

		$mapper = array(); $editable = false;

		$gridSettings = $this->settings->params->grid;
		$grid->itemsPerPage = $gridSettings->itemsPerPage;

		if($gridSettings->drawRowNumber) {
			$mapper['rowNumber'] = 'e.id';
			$grid->addColumn('rowNumber', '#');
			$grid['rowNumber']->formatCallback[] = function() {
				static $c = 1;
				return $c++.'.';
			};
		}

		foreach ($gridSettings->columns as $aliasName => $column) {
			$mapper[$aliasName] = $column->mapper;
			
			if (!isset($column->draw) || (isset($column->draw) && $column->draw == true)) {
				$type = isset($column->type) ? $column->type : 'text';
				$property = substr($column->mapper, strrpos($column->mapper, '.')+1);

				if ($controlAnnotation = $this->reflector->getAnnotation($mainEntityName, $property, Reflector::COLUMN)) {
					$type = $controlAnnotation->type;
				}
				
				switch ($type) {
					case 'datetime':	$grid->addDateColumn($aliasName, $column->label, '%d %b %Y %R (%p)'); break;
					case 'date':		$grid->addDateColumn($aliasName, $column->label, '%d %b %Y'); break; // TODO: poriesit formatovanie datumov
					case 'time':		$grid->addDateColumn($aliasName, $column->label, '%R'); break;
					case 'boolean':		$grid->addCheckboxColumn($aliasName, $column->label); break;
					default:			$grid->addColumn($aliasName, $column->label);
				}

				$alias = $grid->getComponent($aliasName);

				if(isset($column->headerStyle)) {
					$alias->getHeaderPrototype()->style($column->headerStyle);
				}

				if(isset($column->cellStyle)) {
					$alias->getCellPrototype()->style($column->cellStyle);
				}
				if(isset($column->filter)) {
					if(is_scalar($column->filter)) {
						$column->filter = ArrayHash::from(array('type' => $column->filter));
					}
					if($column->filter->type == 'selectbox') {
						$options = NULL;
						if(isset($column->filter->options)) {
							$options = $column->filter->options;
						} else if(isset($column->filter->callback)) {
							$options = callback($this->filter->callback->class, $this->filter->callback->arguments);
						}

						$alias->addSelectboxFilter((array) $options);
					} else if($column->filter->type == 'checkbox') {
						$alias->addCheckboxFilter();
					} else if($column->filter->type == 'date') {
						$alias->addDateFilter();
					} else {
						$alias->addFilter();
					}
				}


				
				if (isset($column->callback)) {
					$column->callback->class == '%this%' ? $column->callback->class = $this : $column->callback->class;
					// debug($column->callback);

					$alias->formatCallback[] = new \DataGrid\Callback(
						$column->callback->class,
						$column->callback->method,
						$this,
						isset($column->callback->params) ? $column->callback->params : NULL
					);
				}
			}
		}

		$list = $this->serviceListName;
		$dsMethod = $gridSettings->dataSourceMethod;
		$dsArguments = $gridSettings->dataSourceArguments;
		if($dsArguments instanceof ArrayHash) $dsArguments = (array) $dsArguments;
		$list = $list::$dsMethod($dsArguments);

		$dataSource = new \DataGrid\DataSources\Doctrine\LalaQueryBuilder($list->getDataSource());

		$dataSource->setMapping($mapper);
		$grid->setDataSource($dataSource);

		foreach ($gridSettings->actionColumns as $alias => $value) {
			if($value === FALSE) continue;
			$grid->addActionColumn($alias, Arrays::get($value, 'name', ''));
			
			if(isset($value->headerStyle)) {
				$grid->getComponent($alias)->getHeaderPrototype()->style($value->headerStyle);
			}

			if(isset($value->cellStyle)) {
				$grid->getComponent($alias)->getCellPrototype()->style($value->cellStyle);
			}
			
			foreach ($value->actions as $actionName => $action) {
				if($action === FALSE) continue;
				$title = Arrays::get($action, 'title', ucfirst($actionName));
				if(is_string($action->title)) {
					$action->title = \Nette\ArrayHash::from(array(
							'label' => $action->title
						));
				}

				if($action->title instanceof \Nette\ArrayHash) {
					$title = Html::el('a')->title($title)->add(Arrays::get($action->title, 'label', ucfirst($title)));

					if(isset($action->class)) $title->class($action->class);
					else $title->class('btn btn-mini');
					if(isset($action->addClass)) $title->addClass($action->addClass);
					if(isset($action->target)) $title->target($action->target);
				}

				$actionComponent = $grid->addAction($title, $actionName, Arrays::get($action, 'ajax', NULL));
			}
		}

		$renderer = $grid->getRenderer();
		$renderer->wrappers['datagrid']['container'] = 'table class="datagrid '.$gridSettings->addClass.'"';
		$renderer->onActionRender[] = callback($this, 'onActionRender');
		
		return $grid;
	}

	public function onActionRender() {
		//debug(func_get_args());
	}

	public function pattern($value, $row, $params = null) {
		$params = $params[1];
		return preg_replace_callback('/%([\w]*)%/', function($matches) use ($row) {
			return isset($row[$matches[1]]) ? $row[$matches[1]] : $matches[0];
		}, $params->pattern);
	}

	public function translateColumn($value, $row, $params) {
		$key = $params[1];
		// debug(func_get_args());
		$entity = $row->getEntity();
		if($key instanceof \Traversable) {
			foreach ($key as $key => $value) {
				$entity = $entity->{$value};
			}
		} else {
			$entity = $entity->{$key};
		}
		// debug($entity);
		return $this->translate($entity->id);
	}
}

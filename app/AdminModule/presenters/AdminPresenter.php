<?php

namespace AdminModule;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Tra\Utils\Arrays,
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
		$this->formMask = $this->reflector->getFormMask();
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
		if(isset($this->params['display']) && $this->params['display'] == 'modal') {
			$this->formMask->form->addClass .= ' ajax';
			$this->setLayout(FALSE);
		}

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

		foreach ($gridSettings->columns as $alias => $column) {
			$mapper[$alias] = $column->mapper;
			
			if (!isset($column->draw) || (isset($column->draw) && $column->draw == true)) {
				$type = isset($column->type) ? $column->type : 'text';
				$property = substr($column->mapper, strrpos($column->mapper, '.')+1);

				if ($controlAnnotation = $this->reflector->getAnnotation($mainEntityName, $property, Reflector::COLUMN)) {
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
		$dataSource = new \DataGrid\DataSources\Doctrine\LalaQueryBuilder($list->getDataSource());

		$dataSource->setMapping($mapper);
		$grid->setDataSource($dataSource);

		foreach ($gridSettings->actionColumns as $key => $value) {
			if($value === FALSE) continue;
			$grid->addActionColumn($key, Arrays::get($value, 'name', ''));
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
				}

				$grid->addAction($title, $actionName, Arrays::get($action, 'ajax', NULL));
			}
			
		}

		$grid->itemsPerPage = $gridSettings->itemsPerPage;
		

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

<?php

namespace AdminModule;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\Utils\Strings,
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
		//$this->serviceName = $this->settings->serviceClass;
		//$this->serviceListName = $this->settings->serviceListClass;
		//$this->reflector = new Reflector($this->settings, $this);
	}
	
	public function getMainServiceName() {
		return $this->serviceName;
	}

	public function renderList() {
		$this->template->settings = $this->settings;
		$this->template->showAddNewButton = $this->settings->params->addNewButton;
		$this->template->directCreating = !isset($this->settings->params->fields);
	}

	public function actionCreate() {

		$r = \Extras\Reflection\Entity\ClassType::from('\Entity\Location\Location')->getPropertiesByType(\Extras\Reflection\Entity\ClassType::COLUMN_PHRASE);
		d($r);
		// $this->service->create($this->formMask, $values);
		//$this->redirect('edit', array('id' => $this->service->id));
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
		$this->template->fomatedH1 = trim(str_replace(array('Entity', '\\'), array('', ' '), $this->formMask->entityReflection->name));
		$this->template->service = $this->service;

		$this->template->setFile(APP_DIR . '/AdminModule/templates/Admin/edit.latte');
	}

	public function actionEdit($id = 0) {
		// $service = $this->serviceName;
		// $this->service = $service::get($id);
		// $this->formMask = $this->reflector->getFormMask($this->service, $this->settings->params->form);
		// debug($this->service);

	}

	
	public function renderEdit($id = 0) {
		$form = $this->getComponent('form');

		//$this->service->setDefaultsFormData($this->reflector->getContainer($form), $this->formMask);

		//$this->template->record = $this->service;
		$this->template->form = $form;
		//$this->template->created = $this->service->created;
		//$this->template->updated = $this->service->updated;
		//$this->template->fomatedH1 = $this->formatText($this->settings->h1, $this->service);
		//$this->template->service = $this->service;
	}
	
	protected function createComponentForm($name) {
		$repositoryName = $this->settings->params->repositoryAccessor;
		$reposiory = $this->context->{$repositoryName}->get();
		$entity = $reposiory->find($this->getParam('id'));

		$model = $this->context->model;
		$configurator = new \Extras\Config\Configurator($this->context->params['settingsDir'] . '/presenters/language.neon');
		$generator = $this->context->createFormGenerator($configurator, $entity)->build();

		$form = new \AdminModule\Forms\AdminForm;

		
		$generator->getMask()->extend($form);

		$form->onSuccess[] = array($generator->getMask(), 'process');
		$form->onSuccess[] = function($form) use ($model) {
			$model->flush();
		};
		$form->onSuccess[] = callback($this, 'formOnSuccess');


		return $form;
	}

	public function formOnSuccess(\AdminModule\Forms\AdminForm $form) {
		// nejaky redirect....
	}

	public function formatText($text, $service) {
		foreach (Strings::matchAll($text, '~%([a-zA-Z]+)%~') as $key => $value) {
			if($service->{$value[1]} instanceof \Entity\Dictionary\Phrase) {
				$v = $this->translate($service->{$value[1]});
			} else {
				$v = $service->{$value[1]};
			}
			$text = str_replace($value[0], $v, $text);
		}
		return $text;
	}

	protected function createComponentGrid() {
		$repositoryName = $this->settings->params->repositoryAccessor;
		$reposiory = $this->context->{$repositoryName};
		$gridClass = '\\AdminModule\\Grids\\'.$this->settings->params->grid->class;
		return new $gridClass($reposiory);
	}

}

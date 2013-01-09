<?php

namespace AdminModule;

use Nette, Extras, Service, Entity, TwiGrid;

class AdminPresenter extends BasePresenter {

	protected $settings;
	protected $repository;

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
		$this->repository = $this->context->model->getRepository($this->getEntityName());
	}

	public function actionList() {

	}

	public function actionAdd() {
		$entity = $this->repository->createNew();
		$this->context->model->persist($entity);
		$this->template->form = $this->getForm($this->getConfigName(), $entity);
		$this->settings->name = 'novééé';
	}

	public function actionEdit($id) {
		$entity = $this->repository->find($id);
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
		return $this->getService('presenter.' . $this->getConfigName() . '.grid')->create($this, $this->repository)->render();
	}
}
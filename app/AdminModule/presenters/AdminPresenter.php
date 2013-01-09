<?php

namespace AdminModule;

use Nette, Extras, Service, Entity, TwiGrid;

/**
 * Vsemohuci automaticky prezenter
 * @author Branislav Vaculčiak <branislav@vauclciak.sk>
 */
class AdminPresenter extends BasePresenter {

	/** @var Extras\Presenter\Settings */
	protected $settings;

	/** @var Doctrine\ORM\EntityRepository */
	protected $repository;

	/**
	 * Vrati nazov entity pre aktualny prezenter
	 * @return string
	 */
	public function getEntityName() {
		return '\\Entity\\' . ucfirst($this->getConfigName());
	}

	/**
	 * Vrati nazov service pre aktualny prezenter
	 * @return string
	 */
	public function getServiceName() {
		return '\\Service\\' . ucfirst($this->getConfigName()) . 'Service';
	}

	/**
	 * Vrati nazov entity pouzivany pre konfiguraky
	 * @return string
	 */
	public function getConfigName() {
		$parts = explode(':', $this->name);
		return strtolower(end($parts));
	}

	/**
	 * Start prezentera
	 */
	public function startup() {
		parent::startup();
		
		$this->settings = $this->getService('presenter.' . $this->getConfigName() . '.settings');
		$this->template->settings = $this->settings;
		$this->repository = $this->context->model->getRepository($this->getEntityName());
	}

	/**
	 * Akcia zoznam
	 */
	public function actionList() {

	}

	/**
	 * Akcia pridania noveho zaznamu
	 */
	public function actionAdd() {
		$entity = $this->repository->createNew();

		// TODO: docasne
		$model = $this->getService('model');
		$model->persist($entity);
		$languageRepo = $this->context->model->getRepository('Entity\\Language');
		$entity->name->createTranslation($languageRepo->findOneByIso('sk'));
		$entity->name->createTranslation($languageRepo->findOneByIso('en'));
	
		$this->template->form = $this->getForm($this->getConfigName(), $entity);
		$this->settings->name = 'novééé';
	}

	/**
	 * Akcia editacie
	 */
	public function actionEdit($id) {
		$entity = $this->repository->find($id);
		$this->template->form = $this->getForm($this->getConfigName(), $entity);
		$this->settings->name = $entity->id . ' - dynamika';
	}

	/**
	 * Vrati formular pre aktualny prezenter
	 */
	public function getForm($name, $entity) {
		$presenter = $this;
		$model = $this->getService('model');
		$form = $this->getService("presenter.$name.form")->create($entity);
		$form->onSuccess[] = function($form) use ($model, $entity, $presenter) {	
			$model->flush();
			$presenter->redirect('edit', $entity->id);
		};
		$this->addComponent($form, $name);
		return $form;
	}

	/**
	 * Komponenta data gridu
	 */
	protected function createComponentDataGrid() {
		return $this->getService('presenter.' . $this->getConfigName() . '.grid')->create($this, $this->repository)->render();
	}
}
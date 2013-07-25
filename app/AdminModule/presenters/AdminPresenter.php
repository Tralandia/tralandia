<?php

namespace AdminModule;

use Nette, Extras, Service, Entity;

/**
 * Vsemohuci automaticky prezenter
 * @author Branislav Vaculčiak <branislav@vauclciak.sk>
 */
class AdminPresenter extends BasePresenter {

	/** @var \Extras\Presenter\Settings */
	protected $settings;

	/** @var \Doctrine\ORM\EntityRepository */
	protected $repository;

	/** @var string */
	protected $gridRenderType = null;

	/**
	 * Vrati nazov entity pouzivany pre konfiguraky
	 * @return string
	 */
	public function getConfigName() {
		$parts = explode(':', $this->name);
		return lcfirst(end($parts));
	}

	/**
	 * Start prezentera
	 */
	public function startup() {
		parent::startup();

		$this->settings = $this->getService('presenter.' . $this->getConfigName() . '.settings');
		$this->template->settings = $this->settings;
		$this->repository = $this->context->model->getRepository($this->settings->getEntityClass());
	}

	/**
	 * Akcia zoznam
	 */
	public function actionList($type = null) {
		$this->template->hideAddNewButton = true;
		$this->gridRenderType = $type;
	}

	/**
	 * Akcia pridania noveho zaznamu
	 */
	public function actionAdd() {
		$entity = $this->repository->createNew();
		$this->getService('model')->persist($entity);
		$this->template->form = $this->getForm($this->getConfigName(), $entity);
		$this->settings->name = 'novééé';
	}

	/**
	 * Akcia editacie
	 */
	public function actionEdit($id) {
		$entity = $this->repository->find($id);
		$this->template->form = $this->getForm($this->getConfigName(), $entity);
		$this->settings->name = $entity->name->getCentralTranslationText();
		$this->settings->id = $entity->id;
	}

	/**
	 * Vrati formular pre aktualny prezenter
	 */
	public function getForm($name, $entity) {
		$presenter = $this;
		$model = $this->getService('model');
		/** @var $formMaskFactory \Extras\FormMask\FormFactory */
		$formMaskFactory = $this->getService("presenter.$name.form");
		$form = $formMaskFactory->create($entity);


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
		$grid = $this->getService('presenter.' . $this->getConfigName() . '.gridFactory')->create();

		return $grid;
	}
}

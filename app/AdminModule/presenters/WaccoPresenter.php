<?php

namespace AdminModule;

use Nette, Extras, Service;

class WaccoPresenter extends BasePresenter {

	public function beforeRender() {
		parent::beforeRender();
		$this->setView('list');
	}

	public function actionList() {
		$repo = $this->context->model->getRepository('Entity\Currency');
		$entity = $repo->find(2);

		$model = $this->context->model;
		$configurator = new Extras\Config\Configurator($this->context->params['settingsDir'] . '/presenters/currency.neon');
		$generator = $this->context->createFormGenerator($configurator, $entity)->build();

		$form = new Nette\Application\UI\Form($this, 'form');
		$form->onSuccess[] = array($generator->getMask(), 'process');
		$form->onSuccess[] = function($form) use ($model) {
			$model->flush();
		};

		$generator->getMask()->extend($form);
		$this->template->form = $form;
	}

	public function actionList2() {
		$repo = $this->context->model->getRepository('Entity\Language');
		$entity = $repo->find(144);

		$form = $this->context->presenter->language->form->create($entity);
		$this->template->form = $form;
	}

	public function actionList3() {
		$repo = $this->context->model->getRepository('Entity\Location\Location');
		$entity = $repo->find(563);
		$form = $this->context->presenter->location->form->create($entity);

		$this->template->form = $form;
		return;


		$configurator = new Extras\Config\Configurator2($this->context->params['settingsDir'] . '/presenters/location.neon');
		$container = $configurator->createContainer();
		debug($container);
		debug($container->form->create($entity));

		exit;
		/*
		$generator = $this->context->createFormGenerator($configurator, $entity)->build();

		$form = new Nette\Application\UI\Form($this, 'form');
		$form->onSuccess[] = array($generator->getMask(), 'process');
		$form->onSuccess[] = function($form) use ($model) {
			$model->flush();
		};

		$generator->getMask()->extend($form);
		$this->template->form = $form;
		*/
	}
}
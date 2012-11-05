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

		$model = $this->context->model;
		$configurator = new Extras\Config\Configurator($this->context->params['settingsDir'] . '/presenters/language.neon');
		$generator = $this->context->createFormGenerator($configurator, $entity)->build();

		$form = new Nette\Application\UI\Form($this, 'form');
		$form->onSuccess[] = array($generator->getMask(), 'process');
		$form->onSuccess[] = function($form) use ($model) {
			$model->flush();
		};

		$generator->getMask()->extend($form);
		$this->template->form = $form;
	}

	public function actionList3() {
		$repo = $this->context->model->getRepository('Entity\Location\Location');
		$entity = $repo->find(563);

		$model = $this->context->model;
		$configurator = new Extras\Config\Configurator($this->context->params['settingsDir'] . '/presenters/location.neon');
		$generator = $this->context->createFormGenerator($configurator, $entity)->build();

		$form = new Nette\Application\UI\Form($this, 'form');
		$form->onSuccess[] = array($generator->getMask(), 'process');
		$form->onSuccess[] = function($form) use ($model) {
			$model->flush();
		};

		$generator->getMask()->extend($form);
		$this->template->form = $form;
	}
}
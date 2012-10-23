<?php

namespace AdminModule;

use Nette, Extras, Service;

class WaccoPresenter extends BasePresenter {

	public function beforeRender() {
		parent::beforeRender();
		$this->setView('list');
	}

	public function actionList() {
		//$locationLocationRepo = $this->context->model->getRepository('Entity\Location\Location');
		//$locationTypeRepo = $this->context->model->getRepository('Entity\Location\Type');
		//$entity = $locationLocationRepo->find(2);

		
		$repo = $this->context->model->getRepository('Entity\Currency');
		$entity = $repo->find(2);

		$model = $this->context->model;
		$configurator = new Extras\Config\Configurator($this->context->params['settingsDir'] . '/presenters/currency.neon');

		$mask = new Extras\FormMask\Mask;
		$form = new Nette\Application\UI\Form($this, 'form');
		$form->onSuccess[] = array($mask, 'process');
		$form->onSuccess[] = function($form) use ($model) {
			$model->flush();
		};

		$generator = new Extras\FormMask\Generator($mask, $configurator, $entity);
		$generator->setItemPhrase($this->context->itemPhraseFactory);
		$generator->setItemText($this->context->itemTextFactory);
		$generator->build();

		$mask->extend($form);
		$this->template->form = $form;
	}
}
<?php

namespace AdminModule;

use Nette, Extras, Service;

class WaccoPresenter extends BasePresenter {

	public function beforeRender() {
		parent::beforeRender();
		$this->setView('list');
	}

	public function actionList() {
		/*
		$repo = $this->context->model->getRepository('Entity\Currency');
		$entity = $repo->find(2);
		$this->template->form = $this->getForm('currency', $entity);
		*/

		$phoneBook = $this->getService('phoneBook');
		$phone = '0918 590 768';

		debug($phoneBook->find($phone));
		debug($phoneBook->getOrCreate($phone));
		debug($phoneBook->find($phone));

		$this->template->form = null;
	}

	public function actionList2() {
		$repo = $this->context->model->getRepository('Entity\Language');
		$entity = $repo->find(144);
		$this->template->form = $this->getForm('language', $entity);
	}

	public function actionList3() {
		$repo = $this->context->model->getRepository('Entity\Location\Location');
		$entity = $repo->find(87);
		$this->template->form = $this->getForm('location', $entity);
	}

	public function getForm($name, $entity) {
		$model = $this->context->model;
		$form = $this->context->presenter->{$name}->form->create($entity);
		$form->onSuccess[] = function($form) use ($model) {
			$model->flush();
		};
		$this->addComponent($form, $name);
		return $form;
	}
}
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
		$this->template->form = $this->context->presenter->currency->form->create($entity);
	}

	public function actionList2() {
		$repo = $this->context->model->getRepository('Entity\Language');
		$entity = $repo->find(144);
		$this->template->form = $this->context->presenter->language->form->create($entity);
	}

	public function actionList3() {
		$repo = $this->context->model->getRepository('Entity\Location\Location');
		$entity = $repo->find(563);
		$this->template->form = $this->context->presenter->location->form->create($entity);
	}
}
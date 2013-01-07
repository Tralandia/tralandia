<?php

namespace AdminModule;

use Nette, Extras, Service;

class AdminPresenter extends BasePresenter {

	public function getServiceName() {
		$parts = explode(':', $this->name);
		return strtolower(end($parts));
	}

	public function startup() {
		parent::startup();
		

		//debug($this->getServiceName());
		$settings = $this->getService('presenter.' . $this->getServiceName() . '.settings');

debug($this->context);

exit;

		//$this->settings = $this->getService('settings');
		//$this->template->settings = $this->settings;
		//$this->serviceName = $this->settings->serviceClass;
		//$this->serviceListName = $this->settings->serviceListClass;
		//$this->reflector = new Reflector($this->settings, $this);
	}

	public function actionList() {
		/*
		$repo = $this->context->model->getRepository('Entity\Currency');
		$entity = $repo->find(2);
		$this->template->form = $this->getForm('currency', $entity);
		*/

/*
		$phoneBook = $this->getService('phoneBook');
		$phone = '0949 888 999';

		debug($phoneBook->find($phone));
		debug($phoneBook->getOrCreate($phone));
		debug($phoneBook->find($phone));
*/

		// $repo = $this->context->model->getRepository('Entity\Test');
		// $entity = $repo->createNew();
		// $this->context->model->persist($entity);
		// $this->template->form = $this->getForm('test', $entity);
	}

}
<?php

namespace AdminModule;

use Nette, Extras;

class WaccoPresenter extends BasePresenter {

	public function beforeRender() {
		parent::beforeRender();
		//$this->setView('list');
	}

	public function actionList() {
		$locationLocationRepo = $this->context->model->getRepository('Entity\Location\Location');
		$locationTypeRepo = $this->context->model->getRepository('Entity\Location\Type');


		$entity = $locationLocationRepo->find(2);
		$form = new Nette\Application\UI\Form($this, 'form');
		$mask = new Extras\FormMask\Mask;
		$form->onSuccess[] = array($mask, 'process');

		$mask->add(Extras\FormMask\Mask::TEXT, 'text', 'Text')
			->setValueGetter(new Extras\Callback(array($this, 'getPhrase'), array($entity)))
			->setValueSetter(array($entity, 'setName'), array($entity));
		$mask->add(Extras\FormMask\Mask::SELECT, 'selektik', 'Selekt')
			->setValueGetter(array($entity, 'getCountry'))
			->setValueSetter(array($entity, 'setCountry'))
			->setItemsGetter(array($locationTypeRepo, 'fetchPairs'), array('id', 'slug'));
		$mask->add(Extras\FormMask\Mask::SUBMIT, 'submit', 'Odoslať');

		$mask->extend($form);
	}

	public function getPhrase($a) {
		debug($a);
		return "b";
	}
}
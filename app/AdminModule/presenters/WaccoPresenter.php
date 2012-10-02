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
			->setValueGetter(new Extras\Callback($this, 'getPhrase', array($entity)))
			->setValueSetter(new Extras\Callback($entity, 'setName', array($entity)));
		$mask->add(Extras\FormMask\Mask::SELECT, 'selektik', 'Selekt')
			->setValueGetter(new Extras\Callback($this, 'getType', array($entity)))
			->setValueSetter(new Extras\Callback($entity, 'setType'))
			->setItemsGetter(new Extras\Callback($locationTypeRepo, 'fetchPairs', array('id', 'slug')));
		$mask->add(Extras\FormMask\Mask::SUBMIT, 'submit', 'Odoslať');

		$mask->extend($form);
		$this->template->form = $form;
	}

	public function getPhrase($entity) {
		return $entity->name->translations->first()->translation;
	}

	public function getType($entity) {
		return $entity->type->id;
	}
}
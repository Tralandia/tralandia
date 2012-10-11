<?php

namespace AdminModule;

use Nette, Extras, Service;

class WaccoPresenter extends BasePresenter {

	/** @var Extras\PresenterSettings */
	private $settings;

	public function startup() {
		parent::startup();
		//$this->settings = new Extras\PresenterSettings('LocationLocation', $this->context->params['settingsDir']);
	}

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


		debug($this->context->createItemPhrase($entity, 'a', 'b'));

return;
		$configurator = new Extras\Config\Configurator($this->context->params['settingsDir'] . '/presenters/currency.neon');
		$mask = new Extras\FormMask\Mask;
		$form = new Nette\Application\UI\Form($this, 'form');
		$form->onSuccess[] = array($mask, 'process');
		$form->onSuccess[] = function($form) {
			debug($form->getValues());
		};

		$generator = $this->context->createFormMaskGenerator($mask, $configurator, $entity);
		$generator->build();

		//formMaskGenerator

		$mask->extend($form);
		$this->template->form = $form;
	}
/*
	public function fetchPairs($entity) {
		return $entity->name->translations->first()->translation;
	}
*/
/*
	public function getPhrase($entity, $property = 'name') {
		$sPhrase = new Service\Dictionary\Phrase($this->context->model, $entity->$property);
		return $sPhrase->getTranslateValue($this->context->environment->getLanguage());
	}

	public function setPhrase($entity, $property = 'name') {
		//$sPhrase = new Service\Dictionary\Phrase($this->context->model, $entity->$property);
		//return $sPhrase->getTranslate($this->context->environment->getLanguage());
	}

	public function getType($entity) {
		return $entity->type->id;
	}
*/
}
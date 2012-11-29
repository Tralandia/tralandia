<?php

namespace FormMask\Items;

use PHPUnit_Framework_TestCase, Nette, Extras, Service;

require_once __DIR__ . '/../../../bootstrap.php';

/**
 * @backupGlobals disabled
 */
class PhraseTest extends PHPUnit_Framework_TestCase
{
	public $context;
	public $model;
	public $language;

	protected function setUp() {
		$this->context = Nette\Environment::getContext();
		$this->model = $this->context->model;
		$this->language = $this->context->environment->getLanguage();
	}

	public function testDefault() {
		$entity = $this->model->getRepository('Entity\Currency')->find(2);
		$service = new Service\Dictionary\Phrase($this->model, $entity->name);
		$oldTranslate = $service->getTranslationText($this->language);

		// vytvorim masku a form
		$mask = new Extras\FormMask\Mask;
		$form = new Nette\Forms\Form;
		$form->onSuccess[] = array($mask, 'process');

		// vytvorim masku
		$item = new Extras\FormMask\Items\Phrase('phrase', 'Phrase');
		$item->setValueGetter(new Extras\Callback($service, 'getTranslationText', array($this->language)));
		$item->setValueSetter(new Extras\Callback($service, 'setTranslationText', array($this->language)));

		// rozsirim formular maskou
		$mask->addItem($item);
		$mask->extend($form);

		// nasetujem ososlane data a vyvolam sucess event
		$form->getComponent('phrase')->setValue('Iná hodnota 98755');
		$form->onSuccess($form);

		$newTranslate = $service->getTranslationText($this->language);

		// overujem spravne ulozenie dat
		$this->assertSame('Iná hodnota 98755', $newTranslate);

		// vratim staru hodnotu naspat
		$service = new Service\Dictionary\Phrase($this->model, $entity->name);
		$oldTranslate = $service->setTranslationText($this->language, $oldTranslate);
	}
}
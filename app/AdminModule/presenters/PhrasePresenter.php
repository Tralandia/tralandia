<?php

namespace AdminModule;


class PhrasePresenter extends BasePresenter {

	/**
	 * @autowire
	 * @var \BaseModule\Forms\ISimpleFormFactory
	 */
	protected $simpleFormFactory;

	protected $phraseRepositoryAccessor;

	public $phrase;

	public function injectDic(\Nette\DI\Container $dic) {
		$this->phraseRepositoryAccessor = $dic->phraseRepositoryAccessor;
	}


	public function actionEdit($id = 0, $fromLanguage = 0, $toLanguage = 0) {
		$this->phrase = $this->phraseRepositoryAccessor->get()->find($id);
	}

	public function actionEditList()
	{

	}


	protected function createComponentPhraseEditForm()
	{
		$phrases = $this->phraseRepositoryAccessor->get()->findBy(['ready' => true], [], 5);


		$form = $this->simpleFormFactory->create();

		$listContainer = $form->addContainer('list');
		foreach($phrases as $phrase) {
			$phraseContainer = $listContainer->addPhraseContainer($phrase->getId(), $phrase);
			$phraseContainer->build();
			$phraseContainer->setDefaultValues();
		}

		$form->addSubmit('save', 'o100151');

		$form->onSuccess[] = callback($this, 'processPhraseEditForm');

		return $form;
	}

	public function processPhraseEditForm($form)
	{
		$values = $form->getValues(TRUE);
		d($values);
		$this->flashMessage('Success', 'success');
		//$this->redirect('this');
	}

}

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


	/**
	 * @return Forms\Dictionary\PhraseEditForm
	 */
	protected function createComponentPhraseEditForm()
	{
		$form = $this->simpleFormFactory->create();

		$form->addPhraseContainer('phrase', $this->loggedUser, $this->phrase);
		
		$form->addSubmit('save', 'o100151');

		$form->onSuccess[] = callback($this, 'processPhraseEditForm');

		return $form;
	}

	/**
	 * @param Forms\Dictionary\PhraseEditForm
	 */
	public function processPhraseEditForm(Forms\Dictionary\PhraseEditForm $form)
	{
		$values = $form->getValues(TRUE);
		d($values);
		$this->flashMessage('Success', 'success');
		//$this->redirect('this');
	}

}

<?php

namespace AdminModule;

use Repository\Dictionary as RD;

class PhrasePresenter extends AdminPresenter {

	protected $phraseRepositoryAccessor;
	protected $phraseTypeRepositoryAccessor;
	protected $languageRepositoryAccessor;

	public $phrase;

	public function setContext(\Nette\DI\Container $dic) {
		parent::setContext($dic);

		$this->setProperty('phraseRepositoryAccessor');
		$this->setProperty('phraseTypeRepositoryAccessor');
		$this->setProperty('languageRepositoryAccessor');
	}
	

	public function actionEdit($id = 0) {
		$this->phrase = $phrase = $this->phraseRepositoryAccessor->get()->find($id);

	}

	public function renderEdit($id = 0) {
		
	}

	/**
	 * @return Forms\Dictionary\PhraseEditForm
	 */
	protected function createComponentPhraseEditForm()
	{
		$sourceLanguage = $this->phrase->sourceLanguage;
		$form = new Forms\Dictionary\PhraseEditForm($this->phraseTypeRepositoryAccessor, $this->languageRepositoryAccessor, $sourceLanguage);
	
		$form->onSuccess[] = callback($this, 'processPhraseEditForm');
	
		return $form;
	}
	
	/**
	 * @param Forms\Dictionary\PhraseEditForm
	 */
	public function processPhraseEditForm(Forms\Dictionary\PhraseEditForm $form)
	{
		$values = $form->getValues();
	
		$this->flashMessage('Success', 'success');
		$this->redirect('this');
	}

}

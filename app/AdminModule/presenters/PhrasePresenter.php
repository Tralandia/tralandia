<?php

namespace AdminModule;

use Repository\Dictionary as RD;

class PhrasePresenter extends AdminPresenter {

	protected $phraseRepositoryAccessor;
	protected $phraseTypeRepositoryAccessor;
	protected $languageRepositoryAccessor;

	public $phrase, $fromLanguage, $toLanguage;

	public function setContext(\Nette\DI\Container $dic) {
		parent::setContext($dic);

		$this->setProperty('phraseRepositoryAccessor');
		$this->setProperty('phraseTypeRepositoryAccessor');
		$this->setProperty('languageRepositoryAccessor');
	}
	

	public function actionEdit($id = 0, $fromLanguage = 0, $toLanguage = 0) {
		$this->phrase = $this->phraseRepositoryAccessor->get()->find($id);
		$this->fromLanguage = $this->languageRepositoryAccessor->get()->find($fromLanguage);
		$this->toLanguage = $this->languageRepositoryAccessor->get()->find($toLanguage);
		$this->template->phrase = $this->phrase;
		$this->template->fromLanguage = $this->fromLanguage;
		$this->template->toLanguage = $this->toLanguage;
	}

	public function renderEdit($id = 0) {
		
	}

	/**
	 * @return Forms\Dictionary\PhraseEditForm
	 */
	protected function createComponentPhraseEditForm()
	{
		$sourceLanguage = $this->phrase->sourceLanguage;
		$form = new Forms\Dictionary\PhraseEditForm($this->fromLanguage, $this->toLanguage, $this->phrase, $this->phraseTypeRepositoryAccessor, $this->languageRepositoryAccessor, $sourceLanguage);
	
		$form->onSuccess[] = callback($this, 'processPhraseEditForm');

		$form->setDefaultValues();
	
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

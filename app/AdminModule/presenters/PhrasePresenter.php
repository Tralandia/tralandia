<?php

namespace AdminModule;

use Repository\Dictionary as RD;

class PhrasePresenter extends AdminPresenter {

	protected $phraseServiceFactory;
	protected $phraseRepositoryAccessor;
	protected $languageRepositoryAccessor;

	public $phrase, $phraseService, $fromLanguage, $toLanguage;

	public function setContext(\Nette\DI\Container $dic) {
		parent::setContext($dic);

		$this->setProperty('phraseServiceFactory');
		$this->setProperty('phraseRepositoryAccessor');
		$this->setProperty('languageRepositoryAccessor');
	}
	

	public function actionEdit($id = 0, $fromLanguage = 0, $toLanguage = 0) {
		$this->phrase = $this->phraseRepositoryAccessor->get()->find($id);
		$this->phraseService = $this->phraseServiceFactory->create($this->phrase);
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
		$form = new Forms\Dictionary\PhraseEditForm($this->phraseService, $this->fromLanguage, $this->toLanguage, $this->languageRepositoryAccessor, $sourceLanguage);
	
		$form->onSuccess[] = callback($this, 'processPhraseEditForm');

		$form->setDefaultValues();
	
		return $form;
	}
	
	/**
	 * @param Forms\Dictionary\PhraseEditForm
	 */
	public function processPhraseEditForm(Forms\Dictionary\PhraseEditForm $form)
	{
		$values = $form->getValues(TRUE);
		$phrase = $this->phraseService->getEntity();
		$phrase->ready = $values['ready'];
		$phrase->corrected = $values['corrected'];
		$phrase->sourceLanguage = $this->languageRepositoryAccessor->get()->find($values['sourceLanguage']);

		$translation = $this->phraseService->getTranslation($this->toLanguage);
		$translation->variations = $values['toTranslations'];
		$translation->gender = $values['gender'];
		$translation->position = $values['position'];

		$this->phraseService->save();

		// d($values);
		$this->flashMessage('Success', 'success');
		$this->redirect('this');
	}

}

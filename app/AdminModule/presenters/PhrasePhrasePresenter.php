<?php

namespace AdminModule;

use Repository\Dictionary as RD;

class PhrasePhrasePresenter extends AdminPresenter {

	protected $phraseRepository;
	protected $phraseTypeRepository;
	protected $languageRepository;

	public $phrase;

	public function setContext(\Nette\DI\Container $dic) {
		parent::setContext($dic);

		$this->phraseRepository = $dic->phraseRepository;
		$this->phraseTypeRepository = $dic->phraseTypeRepository;
		$this->languageRepository = $dic->languageRepository;
	}
	

	public function actionEdit($id = 0) {
		$this->phrase = $phrase = $this->phraseRepository->find($id);

	}

	public function renderEdit($id = 0) {
		
	}

	/**
	 * @return Forms\Dictionary\PhraseEditForm
	 */
	protected function createComponentPhraseEditForm()
	{
		$sourceLanguage = $this->phrase->sourceLanguage;
		$form = new Forms\Dictionary\PhraseEditForm($this->phraseTypeRepository, $this->languageRepository, $sourceLanguage);
	
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

<?php

namespace AdminModule;

use Repository\Dictionary as RD;

class DictionaryPhrasePresenter extends AdminPresenter {

	protected $phraseRepository;
	protected $phraseTypeRepository;
	protected $langaugeRepository;

	public $phrase;

	public function injectRepositories(RD\PhraseRepository $p, RD\TypeRepository $t, RD\LanguageRepository $l) {
		$this->phraseRepository = $p;
		$this->phraseTypeRepository = $t;
		$this->langaugeRepository = $l;
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
		$language = $this->phrase->sourceLanguage;
		$form = new Forms\Dictionary\PhraseEditForm($this->phraseTypeRepository, $this->langaugeRepository, $language);
	
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

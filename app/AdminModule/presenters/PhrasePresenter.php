<?php

namespace AdminModule;


use Entity\User\Role;

class PhrasePresenter extends BasePresenter {

	/**
	 * @autowire
	 * @var \BaseModule\Forms\ISimpleFormFactory
	 */
	protected $simpleFormFactory;

	/**
	 * @autowire
	 * @var \ResultSorter
	 */
	protected $resultSorter;

	/**
	 * @autowire
	 * @var \SupportedLanguages
	 */
	protected $supportedLanguages;

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
		$phrases = $this->phraseRepositoryAccessor->get()->findBy(['type' => 9], [], 5);

		$phraseContainerSettings = [];
		if($this->user->isInRole(Role::TRANSLATOR)) {
			$translatorLanguages = $this->languageRepositoryAccessor->get()->findByTranslator($this->loggedUser);
			$translatorLanguages = $this->resultSorter->translateAndSort($translatorLanguages);
			$phraseContainerSettings['translatorLanguages'] = $translatorLanguages;

			$toLanguages = \Tools::arrayMap(
				$translatorLanguages,
				function($value) {return $value->getIso();},
				function($key, $value) {return $value->getId();}
			);
			$phraseContainerSettings['disableSourceLanguageInput'] = TRUE;
			$phraseContainerSettings['disableReadyInput'] = TRUE;
			$phraseContainerSettings['disableCorrectedInput'] = TRUE;
			$showOptions = FALSE;
		} else {
			$toLanguages = $this->supportedLanguages->getForSelect();
			$showOptions = TRUE;
		}

		$form = $this->simpleFormFactory->create();



		$form->addSelect('toLanguages', '', $toLanguages);
		$form->addCheckbox('showOptions', '')->setDefaultValue($showOptions);


		$listContainer = $form->addContainer('list');
		foreach($phrases as $phrase) {
			$phraseContainer = $listContainer->addPhraseContainer($phrase->getId(), $phrase);
			$phraseContainer->build($phraseContainerSettings);
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

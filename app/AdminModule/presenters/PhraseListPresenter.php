<?php

namespace AdminModule;


use Entity\User\Role;

class PhraseListPresenter extends BasePresenter {

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

	/**
	 * @var \Entity\Phrase\Phrase
	 */
	protected $phrase;

	public function injectDic(\Nette\DI\Container $dic) {
		$this->phraseRepositoryAccessor = $dic->phraseRepositoryAccessor;
	}

	public function actionEdit()
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
			$translator = $this->context->translator;
			$toLanguages = $this->supportedLanguages->getForSelect(
				function($key, $value) {return $value->getId();},
				function($value) use($translator) {return $translator->translate($value->getName());}
			);
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
		$phraseRepository = $this->phraseRepositoryAccessor->get();
		$values = $form->getValues(TRUE);

		foreach($values['list'] as $phraseId => $values) {
			$phraseValues = $form['list'][$phraseId]->getFormattedValues();
		}
		$phraseRepository->flush();
		$this->flashMessage('Success', 'success');
		$this->redirect('this');
	}

}

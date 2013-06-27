<?php

namespace AdminModule;


use Entity\User\Role;
use Nette\Application\BadRequestException;
use Nette\Application\UI\Form;

class TranslationPresenter extends BasePresenter {

	/**
	 * @autowire
	 * @var \Dictionary\UpdateTranslationVariations
	 */
	protected $variationUpdater;



	public function actionUpdateVariations($viewTranslation)
	{
		if($viewTranslation) {
			$translation = $this->findTranslation($viewTranslation);
			$this->template->viewTranslation = $translation;
		}
	}


	protected function createComponentUpdateVariationsForm()
	{
		$form = $this->simpleFormFactory->create();
		$form->addText('translation', 'Translation');
		$form->addSubmit('submit');

		$form->onSuccess[] = $this->updateVariationsFormSuccess;

		return $form;
	}

	public function updateVariationsFormSuccess(Form $form)
	{
		$values = $form->getValues();

		$translation = $this->findTranslation($values->translation);
		$this->variationUpdater->update($translation);

		$this->em->flush();

		$this->redirect('updateVariations', ['viewTranslation' => $translation->getId()]);
	}


	public function actionUpdatePhraseTranslationsVariations()
	{
	}


	protected function createComponentUpdatePhraseTranslationsVariationsForm()
	{
		$form = $this->simpleFormFactory->create();
		$form->addText('phrases', 'Phrases');
		$form->addSubmit('submit');

		$form->onSuccess[] = $this->updatePhraseTranslationsVariationsFormSuccess;

		return $form;
	}

	public function updatePhraseTranslationsVariationsFormSuccess(Form $form)
	{
		$values = $form->getValues();

		$phrases = $values->phrases;
		$phrases = explode(',', $phrases);
		$phrases = array_map('trim', $phrases);

		foreach($phrases as $phrase) {
			$phraseEntity = $this->findPhrase($phrase);
			$this->variationUpdater->updatePhrase($phraseEntity);
		}

		$this->em->flush();

		$this->redirect('updatePhraseTranslationsVariations');
	}

}

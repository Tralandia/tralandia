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

}

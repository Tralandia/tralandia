<?php

namespace AdminModule;


use Entity\User\Role;
use Nette\Application\BadRequestException;
use Nette\Application\UI\Form;

class PhrasePresenter extends BasePresenter {

	/**
	 * @autowire
	 * @var \Service\Phrase\PhraseCreator
	 */
	protected $phraseCreator;

	/**
	 * @autowire
	 * @var \Service\Phrase\PhraseRemover
	 */
	protected $phraseRemover;


	/**
	 * @autowire
	 * @var \Dictionary\UpdateTranslationStatus
	 */
	protected $statusUpdater;

	protected $phrase;


	public function actionAdd($type)
	{
		$phraseRepository = $this->em->getRepository(PHRASE_ENTITY);
		$phraseTypeRepository = $this->em->getRepository(PHRASE_TYPE_ENTITY);
		$type = $phraseTypeRepository->findOneBy(['entityName' => $type]);
		if(!$type) {
			throw new BadRequestException;
		}

		$phrase = $this->phraseCreator->create($type);
		$phraseRepository->save($phrase);
		$this->redirect('PhraseList:searchId', ['id' => $phrase->getId()]);
	}


	public function actionDelete($id)
	{
		$phrase = $this->findPhrase($id);
		$this->phraseRemover->remove($phrase);
		$this->payload->success = TRUE;
		$this->sendPayload();
	}


	/* ------- Update Phrase Status --------- */


	public function actionUpdatePhraseStatus($viewPhrase)
	{
		if($viewPhrase) {
			$phrase = $this->findPhrase($viewPhrase);
			$this->template->viewPhrase = $phrase;

			$parameters = [
				'search' => $phrase->getId(),
				'languageId' => CENTRAL_LANGUAGE,
				'allTypes' => 1,
				'notUsed' => 0,
			];

			$this->template->detailLink = $this->link(':Admin:PhraseList:search', $parameters);
		}
	}


	protected function createComponentPhraseResolveForm()
	{
		$form = $this->simpleFormFactory->create();
		$form->addText('phrase', 'Phrase');
		$form->addSubmit('submit');

		$form->onSuccess[] = $this->phraseResolveFormSuccess;

		return $form;
	}

	public function phraseResolveFormSuccess(Form $form)
	{
		$values = $form->getValues();

		$phrase = $this->findPhrase($values->phrase);
		$this->statusUpdater->resolvePhrase($phrase);

		$this->em->flush();

		$this->redirect('updatePhraseStatus', ['viewPhrase' => $phrase->getId()]);
	}


	/* ------- Set As Up To Date ------ */

	protected function createComponentSetAsUpToDateForm()
	{
		$form = $this->simpleFormFactory->create();
		$form->addText('phrase', 'Phrase');
		$form->addSubmit('submit');

		$form->onSuccess[] = $this->setAsUpToDateFormSuccess;

		return $form;
	}

	public function setAsUpToDateFormSuccess(Form $form)
	{
		$values = $form->getValues();

		$phrase = $this->findPhrase($values->phrase);
		$this->statusUpdater->setAsUpToDate($phrase);

		$this->em->flush();

		$this->redirect('updatePhraseStatus', ['viewPhrase' => $phrase->getId()]);
	}



}

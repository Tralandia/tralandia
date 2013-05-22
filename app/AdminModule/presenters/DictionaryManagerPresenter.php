<?php

namespace AdminModule;


use Listener\RequestTranslationsEmailListener;
use Nette\Application\BadRequestException;

class DictionaryManagerPresenter extends AdminPresenter {

	/**
	 * @autowire
	 * @var \Listener\RequestTranslationsEmailListener
	 */
	protected $requestTranslationsEmailListener;

	/**
	 * @autowire
	 * @var \Listener\RequestTranslationsSystemLogListener
	 */
	protected $requestTranslationsSystemLogListener;

	/**
	 * @var array
	 */
	public $onRequestTranslations = [];

	public function actionRequestTranslations($id)
	{
		$language = $this->languageRepositoryAccessor->get()->find($id);
		if(!$language) {
			throw new BadRequestException;
		}

		$wordsCountToPay = $this->em->getRepository(TRANSLATION_ENTITY)->calculateWordsCountToPay($language);

		$this->requestTranslationsSystemLogListener->onRequestTranslations($language, $wordsCountToPay, $this->loggedUser);
		$this->requestTranslationsEmailListener->onRequestTranslations($language, $wordsCountToPay, $this->loggedUser);

		$this->flashMessage('Request sent!');
		$this->redirect('list');
	}


	public function actionMarkAsPaid($id)
	{
		$language = $this->languageRepositoryAccessor->get()->find($id);
		if(!$language) {
			throw new BadRequestException;
		}


	}


}

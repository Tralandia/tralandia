<?php

namespace AdminModule;


use Entity\Language;
use Listener\RequestTranslationsEmailListener;
use Nette\Application\BadRequestException;
use Nette\Utils\Strings;

class DictionaryManagerPresenter extends AdminPresenter {

	/**
	 * @autowire
	 * @var \Listener\RequestTranslationsEmailListener
	 */
	protected $requestTranslationsEmailListener;

	/**
	 * @autowire
	 * @var \Listener\RequestTranslationsHistoryLogListener
	 */
	protected $requestTranslationsHistoryLogListener;

	/**
	 * @autowire
	 * @var \Dictionary\MarkAsPaid
	 */
	protected $markAsPaid;

	/**
	 * @autowire
	 * @var \Robot\CreateMissingTranslationsRobot
	 */
	protected $createMissingTranslationsRobot;

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

		$this->requestTranslationsHistoryLogListener->onRequestTranslations($language, $wordsCountToPay, $this->loggedUser);
		$this->requestTranslationsEmailListener->onRequestTranslations($language, $wordsCountToPay, $this->loggedUser);

		$this->flashMessage('Request sent!');
		$this->redirect('list');
	}


	public function actionMarkAsPaid($id)
	{
		$language = $this->findLanguage($id);
		$changedIds = $this->markAsPaid->mark($language, $this->loggedUser);

		$this->flashMessage('Done! ' . count($changedIds) . ' translations mark as paid!', self::FLASH_SUCCESS);
		$this->redirect('list');
	}


	public function processSupportNewLanguage($form)
	{
		$values = $form->getValues();
		$language = $this->findLanguage($values->language);
		$language->setSupported(TRUE);
		$this->em->flush();
		$this->redirect('createMissingTranslations', ['id' => $language->getId()]);
	}


	public function actionCreateMissingTranslations($id)
	{
		$language = $this->findLanguage($id);
		$this->createMissingTranslationsRobot->runFor($language);
		if($this->createMissingTranslationsRobot->needToRunFor($language)) {
			$this->redirect('this');
		}
		$this->redirect('list');
	}


	/**
	 * @param $id
	 *
	 * @return \Entity\Language
	 * @throws \Nette\Application\BadRequestException
	 */
	protected function findLanguage($id)
	{
		$language = $this->languageRepositoryAccessor->get()->find($id);
		if(!$language) {
			throw new BadRequestException;
		}

		return $language;
	}

	protected function createComponentSupportNewLanguage()
	{
		$form = $this->simpleFormFactory->create();

		$languageRepository = $this->em->getRepository(LANGUAGE_ENTITY);

		$languages = $languageRepository->findBy(['supported' => Language::NOT_SUPPORTED], ['iso' => 'ASC']);

		$languages = \Tools::arrayMap($languages,
			function($key, $value) {return $value->getId();},
			function($value) {return Strings::upper($value->getIso()) . ' - ' . $value->getName()->getCentralTranslationText();}
		);

		$form->addSelect('language', '', $languages);
		$form->addSubmit('submit', 'Submit');

		$form->onSuccess[] = $this->processSupportNewLanguage;

		return $form;
	}
}

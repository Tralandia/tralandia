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
	 * @var \Listener\TranslationsSetAsPaidHistoryLogListener
	 */
	protected $translationsSetAsPaidHistoryLogListener;

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
	 * @autowire
	 * @var \Dictionary\FindOutdatedTranslations
	 */
	protected $outdatedTranslations;

	/**
	 * @var array
	 */
	public $onRequestTranslations = [];

	public function actionRequestTranslations($id)
	{
		$language = $this->languageDao->get()->find($id);
		if(!$language) {
			throw new BadRequestException;
		}

		$this->requestTranslationsEmailListener->onRequestTranslations($language, $this->loggedUser);

		//$this->flashMessage('Request sent!');
		$this->payload->success = TRUE;
		$this->sendPayload();
	}


	public function actionMarkAsPaid($id)
	{
		$language = $this->findLanguage($id);
		$changedIds = $this->markAsPaid->mark($language, $this->loggedUser);

		$this->translationsSetAsPaidHistoryLogListener->onMarkAsPaid($language, $changedIds, $this->loggedUser);

		$this->flashMessage('Done! ' . count($changedIds) . ' translations mark as paid!', self::FLASH_SUCCESS);
		//$this->redirect('list');
		$this['dataGrid']['grid']->invalidateRow($id);
		$this->setView('list');
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
		$this->sendPayload();
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


		$form->addSelect('language', 'Add new supported language: ', $languages);
		$form->getElementPrototype()->addClass('form-horizontal adminForm');
		$form->addSubmit('submit', 'Submit');

		$form->onSuccess[] = $this->processSupportNewLanguage;

		return $form;
	}
}

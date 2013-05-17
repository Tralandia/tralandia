<?php

namespace AdminModule;


use Entity\Currency;
use Entity\Phrase\Translation;
use Entity\User\Role;
use Nette\Application\BadRequestException;

class PhraseListPresenter extends BasePresenter {

	/**
	 * @var int
	 */
	protected $itemsPerPage = 30;

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

	/**
	 * @autowire
	 * @var \Dictionary\FindOutdatedTranslations
	 */
	protected $findOutdatedTranslations;

	/**
	 * @var \Repository\Phrase\PhraseRepository
	 */
	protected $phraseRepository;

	/**
	 * @var array|\Entity\Phrase\Phrase[]
	 */
	protected $phrases;

	/**
	 * @var array
	 */
	protected $editableLanguages;

	public function injectDic(\Nette\DI\Container $dic) {
		$this->phraseRepository = $dic->phraseRepositoryAccessor->get();
	}


	public function startup()
	{
		parent::startup();
		if($this->user->isInRole(Role::TRANSLATOR)) {
			$this->itemsPerPage = 10;
		}
		$this->setView('defaultEditList');
	}


	public function actionNotReady()
	{
		$paginator = $this->getPaginator();
		$paginator->setItemCount($this->phraseRepository->getNotReadyCount());

		$qb = $this->phraseRepository->findNotReadyQb();
		$qb->setMaxResults($this->itemsPerPage)
			->setFirstResult($paginator->getOffset());
		$this->phrases = $qb->getQuery()->getResult();
	}


	public function actionNotChecked($id)
	{
		$languageIso = $id;
		if(!$languageIso) {
			throw new BadRequestException;
		}

		$language = $this->languageRepositoryAccessor->get()->findOneByIso($languageIso);

		$paginator = $this->getPaginator();
		$paginator->setItemCount($this->phraseRepository->getNotCheckedCount($language));

		$qb = $this->phraseRepository->findNotCheckedQb($language);
		$qb->setMaxResults($this->itemsPerPage)
			->setFirstResult($paginator->getOffset());
		$this->phrases = $qb->getQuery()->getResult();

		$editForm = $this['phraseEditForm'];
		$editForm->setDefaults([
			'toLanguages' => $language->getId()
		]);
	}


	public function actionToTranslate($id)
	{
		$to = $id;
		if(!$to) {
			$language = $this->languageRepositoryAccessor->get()->findOneByTranslator($this->loggedUser);
			$this->redirect('this', ['id' => $language->getIso()]);
		}


		$language = $this->languageRepositoryAccessor->get()->findOneByIso($to);

		$paginator = $this->getPaginator();
		$paginator->setItemCount($this->findOutdatedTranslations->getWaitingForTranslationCount($language));

		$translations = $this->findOutdatedTranslations->getWaitingForTranslation($language, $this->itemsPerPage, $paginator->offset);
		$this->phrases = \Tools::arrayMap($translations, function($v){return $v->getPhrase();});

		$this->editableLanguages = [$language];

		$editForm = $this['phraseEditForm'];
		$editForm->setDefaults([
			'toLanguages' => $language->getId()
		]);

		$editForm['toLanguages']->setDisabled();
	}


	public function actionDefault()
	{
		$this->phrases = $this->phraseRepository->findBy(['type' => 9], [], 5);

	}


	protected function createComponentPhraseEditForm()
	{

		$phraseContainerSettings = [];

		if($this->user->isInRole(Role::TRANSLATOR)) {
			$translatorLanguages = $this->languageRepositoryAccessor->get()->findByTranslator($this->loggedUser);
			$translatorLanguages = $this->resultSorter->translateAndSort($translatorLanguages);
			$phraseContainerSettings['editableLanguages'] = $translatorLanguages;

			$toLanguages = \Tools::arrayMap(
				$translatorLanguages,
				function($value) {return $value->getIso();},
				function($key, $value) {return $value->getId();}
			);
			$phraseContainerSettings['disableSourceLanguageInput'] = TRUE;
			$phraseContainerSettings['disableStatusSelect'] = TRUE;
			$showOptions = FALSE;
		} else {
			$translator = $this->context->translator;
			$toLanguages = $this->supportedLanguages->getForSelect(
				function($key, $value) {return $value->getId();},
				function($value) use($translator) {return $translator->translate($value->getName());}
			);
			$showOptions = TRUE;
		}

		if(is_array($this->editableLanguages)) {
			$phraseContainerSettings['editableLanguages'] = $this->editableLanguages;
		}

		$form = $this->simpleFormFactory->create();



		$form->addSelect('toLanguages', '', $toLanguages);
		$form->addCheckbox('showOptions', '')->setDefaultValue($showOptions);


		$listContainer = $form->addContainer('list');
		foreach($this->phrases as $phrase) {
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
		$phraseRepository = $this->phraseRepository;
		$values = $form->getValues(TRUE);

		foreach($values['list'] as $phraseId => $values) {
			$phraseValues = $form['list'][$phraseId]->getFormattedValues();
			//$phrase = $phraseValues['phrase'];

			if($this->user->isInRole(Role::TRANSLATOR)) {
				/** @var $translation \Entity\Phrase\Translation */
				foreach($phraseValues['changedTranslations'] as $translation) {
					$translation->setStatus(Translation::WAITING_FOR_CHECKING);
				}
			}
		}

		$phraseRepository->flush();
		$this->flashMessage('Success', 'success');
		$this->redirect('this');
	}


	protected function getPaginator()
	{
		/** @var $vp \VisualPaginator */
		$vp = $this['vp'];
		return $vp->getPaginator();
	}


	protected function createComponentVp()
	{
		$vp = new \VisualPaginator();
		$vp->templateFile = APP_DIR.'/FrontModule/components/VisualPaginator/paginator.latte';
		$paginator = $vp->getPaginator();
		$paginator->setItemsPerPage($this->itemsPerPage);

		return $vp;
	}

}

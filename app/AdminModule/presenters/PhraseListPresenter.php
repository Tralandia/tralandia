<?php

namespace AdminModule;


use Dictionary\TranslationsNotCompleteException;
use Entity\Currency;
use Entity\Language;
use Entity\Phrase\Phrase;
use Entity\Phrase\Translation;
use Entity\User\Role;
use Extras\Cache\Cache;
use Nette\Application\BadRequestException;
use Nette\Utils\Arrays;
use Nette\Utils\Strings;

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
	 * @var \Dictionary\FulltextSearch
	 */
	protected $fulltextSearch;

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
	 * @autowire
	 * @var \Dictionary\UpdateTranslationStatus
	 */
	protected $updateTranslationStatus;

	/**
	 * @var \Repository\Phrase\PhraseRepository
	 */
	protected $phraseRepository;

	/**
	 * @var array|\Entity\Phrase\Phrase[]
	 */
	protected $phrases;


	/**
	 * @var array|bool
	 */
	protected $specialOption;

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


	public function actionWaitingForCentral()
	{
		$language = $this->languageRepositoryAccessor->get()->find(CENTRAL_LANGUAGE);

		$paginator = $this->getPaginator();
		$paginator->setItemCount($this->phraseRepository->getCountByStatus(Phrase::WAITING_FOR_CENTRAL));

		$qb = $this->phraseRepository->findByStatusQb(Phrase::WAITING_FOR_CENTRAL);
		$qb->setMaxResults($this->itemsPerPage)
			->setFirstResult($paginator->getOffset());
		$this->phrases = $qb->getQuery()->getResult();

		$this->specialOption = [
			'label' => 'Ready for EN correction',
			'type' => 'readyForCorrection',
		];

		$this->enableOnlyOneLanguage($language);

		$this->template->headerText = 'Not ready for EN correction';

	}


	public function actionNotReady()
	{
		$language = $this->languageRepositoryAccessor->get()->find(CENTRAL_LANGUAGE);

		$paginator = $this->getPaginator();
		$paginator->setItemCount($this->phraseRepository->getCountByStatus(Phrase::WAITING_FOR_CORRECTION_CHECKING));

		$qb = $this->phraseRepository->findByStatusQb(Phrase::WAITING_FOR_CORRECTION_CHECKING);
		$qb->setMaxResults($this->itemsPerPage)
			->setFirstResult($paginator->getOffset());
		$this->phrases = $qb->getQuery()->getResult();

		$this->specialOption = [
			'label' => 'Ready',
			'type' => 'ready',
		];

		$this->enableOnlyOneLanguage($language);

		$this->template->headerText = 'Not Ready Phrases';

	}


	public function actionNotCheckedTranslations($id)
	{
		$languageIso = $id;
		if(!$languageIso) {
			throw new BadRequestException;
		}

		/** @var $language \Entity\Language */
		$language = $this->languageRepositoryAccessor->get()->findOneByIso($languageIso);

		$paginator = $this->getPaginator();
		$paginator->setItemCount($this->phraseRepository->getNotCheckedCount($language));

		$qb = $this->phraseRepository->findNotCheckedTranslationsQb($language);
		$qb->setMaxResults($this->itemsPerPage)
			->setFirstResult($paginator->getOffset());
		$this->phrases = $qb->getQuery()->getResult();

		$this->specialOption = [
			'label' => 'Checked',
			'type' => 'checked',
		];

		$this->enableOnlyOneLanguage($language);

		$this->template->headerText = 'Checking ' . Strings::upper($language->getIso());
	}


	public function actionToTranslate($id)
	{
		$to = $id;
		if(!$to) {
			$language = $this->languageRepositoryAccessor->get()->findOneByTranslator($this->loggedUser);
			$this->redirect('this', ['id' => $language->getIso()]);
		}


		/** @var $language \Entity\Language */
		$language = $this->languageRepositoryAccessor->get()->findOneByIso($to);

		$paginator = $this->getPaginator();
		$paginator->setItemCount($this->findOutdatedTranslations->getWaitingForTranslationCount($language));

		$translations = $this->findOutdatedTranslations->getWaitingForTranslation($language, $this->itemsPerPage, $paginator->offset);
		$this->phrases = \Tools::arrayMap($translations, function($v){return $v->getPhrase();});


		$this->specialOption = [
			'label' => 'Complete',
			'type' => 'translated',
		];

		$this->enableOnlyOneLanguage($language);

		$this->template->headerText = 'Translations to ' . Strings::upper($language->getIso());
	}


	public function actionSearch($search, $languageId, $allTypes, $notUsed)
	{
		$allTypes = (bool) $allTypes;
		$language = $this->languageRepositoryAccessor->get()->find($languageId);
		if(!$language) {
			throw new BadRequestException;
		}


		$paginator = $this->getPaginator();
		$paginator->setItemCount($this->fulltextSearch->getResultCount($search, $language, $allTypes, $notUsed));

		$translations = $this->fulltextSearch->getResult($search, $language, $allTypes, $notUsed, $this->itemsPerPage, $paginator->offset);
		$this->phrases = \Tools::arrayMap($translations, function($v){return $v->getPhrase();});

		$editForm = $this['phraseEditForm'];
		$editForm->setDefaults([
			'toLanguages' => $language->getId()
		]);

		//$editForm['toLanguages']->setDisabled();
		$this->template->headerText = 'Search: ' . $search;
	}


	public function actionSearchId($id)
	{
		$language = $this->loggedUser->getLanguage();
		if(!$language) {
			throw new BadRequestException;
		}


		$paginator = $this->getPaginator();
		$paginator->setItemCount(1);

		$this->phrases = $this->phraseRepository->findById($id);

		$editForm = $this['phraseEditForm'];
		$editForm->setDefaults([
			'toLanguages' => $language->getId()
		]);

		//$editForm['toLanguages']->setDisabled();
		$this->template->headerText = 'Search for ID: ' . $id;
	}


	public function actionDefault()
	{
		$this->phrases = $this->phraseRepository->findBy(['type' => 9], [], 5);

	}


	protected function enableOnlyOneLanguage(Language $language)
	{
		$this->editableLanguages = [$language];

		$editForm = $this['phraseEditForm'];
		$editForm->setDefaults([
			'toLanguages' => $language->getId()
		]);

		$editForm['toLanguages']->setDisabled();
	}


	protected function createComponentPhraseEditForm()
	{

		$phraseContainerSettings = [];

		$translator = $this->context->translator;
		if($this->user->isInRole(Role::TRANSLATOR)) {
			$translatorLanguages = $this->languageRepositoryAccessor->get()->findByTranslator($this->loggedUser);
			$translatorLanguages = $this->resultSorter->translateAndSort($translatorLanguages);
			$phraseContainerSettings['editableLanguages'] = $translatorLanguages;

			$toLanguages = \Tools::arrayMap(
				$translatorLanguages,
				function($key, $value) {return $value->getId();},
				function($value) use($translator) {return Strings::upper($value->getIso()) . ' - ' . $translator->translate($value->getName());}
			);
			$showOptions = FALSE;
		} else {
			$toLanguages = $this->supportedLanguages->getForSelect(
				function($key, $value) {return $value->getId();},
				function($value) use($translator) {return Strings::upper($value->getIso()) . ' - ' . $translator->translate($value->getName());}
			);
			$showOptions = TRUE;
			$phraseContainerSettings['isAdmin'] = TRUE;
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

			if($this->specialOption) {
				$phraseContainer->addCheckbox('specialOptionValue', $this->specialOption['label']);
				$phraseContainer->addHidden('specialOptionType', $this->specialOption['type']);
			}
		}

		$form->addSubmit('save', 'o100151');

		$form->onSuccess[] = callback($this, 'processPhraseEditForm');

		return $form;
	}

	public function processPhraseEditForm($form)
	{
		$phraseRepository = $this->phraseRepository;
		$values = $form->getValues(TRUE);

		$phrasesIds = [];
		foreach($values['list'] as $phraseId => $values) {
			$specialOptionType = Arrays::get($values,'specialOptionType', NULL);
			$specialOptionValue = Arrays::get($values,'specialOptionValue', NULL);
			$phraseValues = $form['list'][$phraseId]->getFormattedValues();
			$phrase = $phraseValues['phrase'];

			if($specialOptionType == 'translated' && $specialOptionValue) {
				foreach($phraseValues['displayedTranslations'] as $translation) {
					try {
						$this->updateTranslationStatus->translationUpdated($translation, $this->loggedUser);
					} catch(TranslationsNotCompleteException $e) {
						$this->flashMessage('Translation #' . $translation->getId() . ' is not translated completely. Please correct / complete it.');
					}
				}
			} else if($specialOptionType == 'checked' && $specialOptionValue) {
				foreach($phraseValues['displayedTranslations'] as $translation) {
					$translation->setStatus(Translation::WAITING_FOR_PAYMENT);
				}
			} else if($specialOptionType == 'ready' && $specialOptionValue) {
				$this->updateTranslationStatus->setPhraseReady($phrase, $this->loggedUser);
			} else if($specialOptionType == 'readyForCorrection' && $specialOptionValue) {
				$this->updateTranslationStatus->setPhraseReadyForCorrection($phrase, $this->loggedUser);
			} else {
				foreach($phraseValues['changedTranslations'] as $translation) {
					try {
						$this->updateTranslationStatus->translationUpdated($translation, $this->loggedUser);
					} catch(TranslationsNotCompleteException $e) {
						$this->flashMessage('Translation #' . $translation->getId() . ' is not translated completely. Please correct / complete it.');
					}
				}
			}
			$phrasesIds[] = $phraseId;
		}

		$phraseRepository->flush();

		//$this->invalidatePhrasesCache($phrasesIds); @todo docasne vypnute, aby to nespomalovalo

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

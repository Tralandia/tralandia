<?php

namespace AdminModule\Components;

use BaseModule\Components\BaseControl,
	Nette\ArrayHash,
	Nette\Utils\Arrays,
	Nette\Utils\Strings;
use BaseModule\Forms\ISimpleFormFactory;
use Dictionary\FindOutdatedTranslations;
use Doctrine\ORM\EntityManager;
use Entity\User\Role;
use Environment\Collator;
use Environment\Environment;
use Nette\Security\User;
use Tralandia\Language\Languages;

class NavigationControl extends BaseControl
{

	protected $navigation;

	/**
	 * @var \Nette\Security\User
	 */
	protected $user;

	/**
	 * @var \Entity\User\User
	 */
	protected $loggedUser;

	/**
	 * @var \BaseModule\Forms\ISimpleFormFactory
	 */
	protected $simpleFormFactory;

	/**
	 * @var \Repository\LanguageRepository
	 */
	protected $languageRepository;

	/**
	 * @var \Environment\Collator
	 */
	protected $collator;

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	private $em;

	/**
	 * @var \Dictionary\FindOutdatedTranslations
	 */
	private $outdatedTranslations;

	/**
	 * @var \Tralandia\Language\Languages
	 */
	private $languages;


	/**
	 * @param User $user
	 * @param ISimpleFormFactory $simpleFormFactory
	 * @param \Tralandia\Language\Languages $languages
	 * @param \Doctrine\ORM\EntityManager $em
	 * @param \Environment\Collator $collator
	 * @param \Dictionary\FindOutdatedTranslations $outdatedTranslations
	 */
	public function __construct(User $user, ISimpleFormFactory $simpleFormFactory, Languages $languages, EntityManager $em,
								Collator $collator, FindOutdatedTranslations $outdatedTranslations)
	{
		$this->user = $user;
		$this->simpleFormFactory = $simpleFormFactory;
		$this->languageRepository = $em->getRepository(LANGUAGE_ENTITY);
		$this->loggedUser = $em->getRepository(USER_ENTITY)->find($user->getId());
		$this->collator = $collator;
		$this->em = $em;
		$this->outdatedTranslations = $outdatedTranslations;
		$this->languages = $languages;
	}


	public function render()
	{
		$template = $this->template;

		$template->userRole = $this->user->isInRole(Role::TRANSLATOR) ? 'Translator' : 'Admin';
		$template->userEmail = $this->user->getIdentity()->login;

		$template->leftItems = $this->prepareNavigation($this->getNavigation()->left);
		$template->rightItems = $this->prepareNavigation($this->getNavigation()->right);
		$template->render();
	}


	public function prepareNavigation($navigation)
	{
		foreach ($navigation as $key => $value) {
			if ($value === NULL) {
				$navigation->$key = $value = new ArrayHash;
			}
			if (!isset($value->class)) {
				$value->class = '';
			}

			if (!isset($value->label)) {
				$value->label = ucfirst($key);
			}
			if (!isset($value->target)) {
				$value->target = NULL;
			}
			if (!isset($value['data-confirm-text'])) {
				$value['data-confirm-text'] = NULL;
			}
			if (isset($value->link)) {
				$linkArgs = (isset($value->linkArgs) ? $value->linkArgs : array());
				$value->href = $this->presenter->link($value->link,(array) $linkArgs);
			}

			if (isset($value->items)) {
				$value->items = $this->prepareNavigation($value->items);
			}
		}

		return $navigation;
	}


	public function getNavigation()
	{
		if (!$this->navigation) {
			$this->navigation = $this->loadNavigation();
			$this->extendNavigation();
		}

		return $this->navigation;
	}


	public function extendNavigation()
	{
		$navigation = $this->getNavigation();
		if($this->user->isInRole(Role::TRANSLATOR)) {
			/** @var $translationRepository \Repository\Phrase\TranslationRepository */
			$translationRepository = $this->em->getRepository(TRANSLATION_ENTITY);
			$left = [];
			$languages = $this->em->getRepository(LANGUAGE_ENTITY)->findByTranslator($this->loggedUser);
			foreach($languages as $language) {
				$languageTree = [];
				$languageTree['label'] = $language->getName()->getCentralTranslationText() . ' (' . $language->getIso() . ')';

				$toTranslateCount = $this->outdatedTranslations->getWaitingForTranslationCount($language);
				$languageItems = [];
				$languageItems['toTranslate'] = [
					'label' => "To Translate ($toTranslateCount)",
					'link' => 'PhraseList:toTranslate',
					'linkArgs' => ['id' => $language->getIso()],
				];

				$languageItems['translatedWords'] = [
					'label' => "Translated"
				];

				$languageTree['items'] = $languageItems;

				$left[$language->getIso()] = $languageTree;
			}
			$left = ArrayHash::from($left);
			$navigation->left = $left;

		}
		if($this->user->getIdentity()->isFake()) {
			$link = [
				'icon' => 'icon-undo',
				'label' => 'Switch back to ' . $this->user->identity->getOriginalIdentity()->login,
				'link' => 'restoreOriginalIdentity',
			];
			$navigation->right->account->items->restoreOriginalIdentity = ArrayHash::from($link);
		}
	}


	public function createComponentSearchForm()
	{
		$form = $this->simpleFormFactory->create();

		$form->setMethod($form::GET);

		$parameters = [
			'search' => '__query__',
			'languageId' => '__languageId__',
			'searchInUserContent' => '__searchInUserContent__',
		];

		if($this->loggedUser->isSuperAdmin()) {
			$rentalLink = $this->presenter->link(':Admin:Rental:list', ['dataGrid-grid-filter' => ['search' => '__query__']]);
			$form->addText('rental', '')
				->getControlPrototype()
				->data('redirect', $rentalLink);
		}


		$phraseLink = $this->presenter->link(':Admin:PhraseList:search', $parameters);
		$form->addText('phrase', '')
			->getControlPrototype()
				->data('redirect', $phraseLink);


		$languages = [];
		$defaultLanguage = $this->loggedUser->getLanguage()->getId();
		if($this->loggedUser->isTranslator()) {
			$rows = $this->languageRepository->findByTranslator($this->loggedUser);
			$defaultLanguage = reset($rows)->getId();
			$en = $this->languageRepository->find(CENTRAL_LANGUAGE);
			array_push($rows, $en);
		} else {
			$rows = $this->languages->findSupported();
		}

		/** @var $row \Entity\Language */
		foreach($rows as $row) {
			$languages[$row->getId()] = Strings::upper($row->getIso());
		}
		$this->collator->asort($languages);

		$defaultLanguage = $this->getParent()->getParameter('languageId', $defaultLanguage);
		$form->addSelect('languages', '', $languages)
			->setDefaultValue($defaultLanguage);
			//->setPrompt('all'); vykomentovane, treba premysliet UI ak najdem hladany vyraz vo viacerich jazykoch...

		$form->addCheckbox('searchInUserContent', '');


		return $form;
	}


	private function loadNavigation()
	{
		$config = new \Nette\DI\Config\Loader;

		$file = $this->user->getRoles()[0];

		$navigationConfig = $config->load(APP_DIR . '/configs/adminNavigation/'.$file.'.neon', 'common');

		return ArrayHash::from($navigationConfig);
	}

}

interface INavigationControlFactory {

	/**
	 * @param User $user
	 *
	 * @return NavigationControl
	 */
	public function create(User $user);
}

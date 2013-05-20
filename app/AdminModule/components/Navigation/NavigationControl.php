<?php

namespace AdminModule\Components;

use BaseModule\Components\BaseControl,
	Nette\ArrayHash,
	Nette\Utils\Arrays,
	Nette\Utils\Strings;
use BaseModule\Forms\ISimpleFormFactory;
use Doctrine\ORM\EntityManager;
use Entity\User\Role;
use Environment\Collator;
use Environment\Environment;
use Nette\Security\User;

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
	 * @param User $user
	 * @param ISimpleFormFactory $simpleFormFactory
	 * @param \Doctrine\ORM\EntityManager $em
	 * @param \Environment\Collator $collator
	 */
	public function __construct(User $user, ISimpleFormFactory $simpleFormFactory, EntityManager $em, Collator $collator)
	{
		$this->user = $user;
		$this->simpleFormFactory = $simpleFormFactory;
		$this->languageRepository = $em->getRepository(LANGUAGE_ENTITY);
		$this->loggedUser = $em->getRepository(USER_ENTITY)->find($user->getId());
		$this->collator = $collator;
		$this->em = $em;
	}


	public function render()
	{
		$template = $this->template;

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
			if (isset($value->target)) {
				$value->target = 'target="' . $value->target . '"';
			} else {
				$value->target = '';
			}
			if (isset($value->link)) {
				$linkArgs = (isset($value->linkArgs) ? $value->linkArgs : array());
				$value->href = $this->presenter->link($value->link,(array) $linkArgs);
			} else {
				$value->href = '#';
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
			$left = [];
			$languages = $this->em->getRepository(LANGUAGE_ENTITY)->findByTranslator($this->loggedUser);
			foreach($languages as $language) {
				$languageTree = [];
				$languageTree['label'] = $language->getName()->getCentralTranslationText() . ' (' . $language->getIso() . ')';

				$languageItems = [];
				$languageItems['toTranslate'] = [
					'label' => 'To Translate',
					'link' => 'PhraseList:toTranslate',
					'linkArgs' => ['to' => $language->getIso()],
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

		$rentalLink = $this->presenter->link(':Admin:Rental:list', ['dataGrid-grid-filter' => ['search' => '__query__']]);
		$form->addText('rental', '')
			->getControlPrototype()
				->data('redirect', $rentalLink);

		$phraseLink = $this->presenter->link(':Admin:PhraseList:search',['search' => '__query__', 'languageId' => '__languageId__']);
		$form->addText('phrase', '')
			->getControlPrototype()
				->data('redirect', $phraseLink);

		$languages = $this->languageRepository->getForAdminSearch($this->collator);
		$form->addSelect('languages', '', $languages);
			//->setPrompt('all'); vykomentovane, treba premysliet UI ak najdem hladany vyraz vo viacerich jazykoch...

		return $form;
	}


	private function loadNavigation()
	{
		$config = new \Nette\Config\Loader;

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

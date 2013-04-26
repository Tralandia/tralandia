<?php

namespace AdminModule\Components;

use BaseModule\Components\BaseControl,
	Nette\ArrayHash,
	Nette\Utils\Arrays,
	Nette\Utils\Strings;
use BaseModule\Forms\ISimpleFormFactory;
use Doctrine\ORM\EntityManager;
use Environment\Collator;
use Environment\Environment;
use Nette\Security\User;

class NavigationControl extends BaseControl
{

	protected $navigation;

	protected $autopilotRegime;

	/**
	 * @var \Nette\Security\User
	 */
	protected $user;

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
		$this->collator = $collator;
	}


	public function render()
	{
		$template = $this->template;

		$this->autopilotRegime = Strings::endsWith($this->getPresenter()->name, ':Ap');

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
			if ($this->autopilotRegime) {
				$value->class .= 'inIframe';
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
				$value->href = $this->presenter->link($value->link, $linkArgs);
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
		if ($this->user->isLoggedIn()) {
			$identity = $this->user->getIdentity();
			// $navigation->right->account->label = $identity->login;
		}
	}


	public function createComponentSearchForm()
	{
		$form = $this->simpleFormFactory->create();

		$rentalLink = $this->presenter->link(':Admin:Rental:search', ['query' => '__query__']);
		$form->addText('rental', '')
			->getControlPrototype()
				->data('redirect', $rentalLink);

		$userLink = $this->presenter->link(':Admin:User:search', ['query' => '__query__']);
		$form->addText('user', '')
			->getControlPrototype()
				->data('redirect', $userLink);


		$phraseLink = $this->presenter->link(':Admin:Phrase:search', ['query' => '__query__', 'languageId' => '__languageId__']);
		$form->addText('phrase', '')
			->getControlPrototype()
				->data('redirect', $phraseLink);

		$languages = $this->languageRepository->getForAdminSearch($this->collator);
		$form->addSelect('languages', '', $languages);

		return $form;
	}


	private function loadNavigation()
	{
		$config = new \Nette\Config\Loader;

		return ArrayHash::from($config->load(APP_DIR . '/configs/navigation.neon', 'common'));
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

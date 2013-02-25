<?php

namespace BaseModule\Forms\Sign;

use Entity\User\Role;
use Nette;

class InForm extends \BaseModule\Forms\BaseForm {

	/**
	 * @var \Extras\Models\Repository\RepositoryAccessor
	 */
	protected $userRepositoryAccessor;

	/**
	 * @param \Extras\Models\Repository\RepositoryAccessor $userRepositoryAccessor
	 * @param \Nette\Localization\ITranslator $translator
	 */
	public function __construct(\Extras\Models\Repository\RepositoryAccessor $userRepositoryAccessor, Nette\Localization\ITranslator $translator)
	{
		$this->userRepositoryAccessor = $userRepositoryAccessor;
		parent::__construct($translator);
	}

	protected function buildForm() {
		$this->addText('login', 'Login:');
		$this->addPassword('password', 'Password');

		$this->addSubmit('submit', 'SignIn');

		$this->onSuccess[] = callback($this, 'onSuccess');
	}

	public function setDefaultsValues()
	{

	}

	public function onSuccess(InForm $form) {
		$values = $form->getValues();
		try {
			$presenter = $this->getPresenter();
			/** @var $user \Nette\Security\User */
			$user = $presenter->getUser();
			$user->setExpiration('+ 30 days', FALSE);
			$user->login($values->login, $values->password);
			if($user->isInRole(Role::OWNER)) {
				/** @var $userEntity \Entity\User\User */
				$userEntity = $this->userRepositoryAccessor->get()->find($user->getId());
				$rental = $userEntity->getFirstRental();
				if($rental instanceof \Entity\Rental\Rental) {
					$presenter->redirect(':Owner:Rental:edit', ['id' => $rental->getId()]);
				}
			}

			if ($homepage = $user->getIdentity()->homepage){
				$presenter->redirect($homepage);
			}
			$presenter->redirect('this');
		} catch(\Nette\Security\AuthenticationException $e) {
			$form->addError('#zle prihlasovacie udaje');
		}
	}

}

interface IInFormFactory {
	/**
	 * @return InForm
	 */
	public function create();
}

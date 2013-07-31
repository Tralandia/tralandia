<?php

namespace OwnerModule;


class UserPresenter extends BasePresenter {

	/**
	 * @autowire
	 * @var \OwnerModule\Forms\IUserEditFormFactory
	 */
	protected $userEditFormFactory;

	public function actionEdit()
	{

	}


	public function createComponentUserEditForm()
	{
		$form = $this->userEditFormFactory->create($this->loggedUser);

		$form->onSuccess[] = function ($form) {
			$form->presenter->flashMessage($form->presenter->translate('542'),UserPresenter::FLASH_INFO);
			$form->presenter->redirect('this');
		};

		return $form;
	}

}

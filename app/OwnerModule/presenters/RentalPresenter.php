<?php

namespace OwnerModule;


use Nette\Application\BadRequestException;
use Nette\Environment;

class RentalPresenter extends BasePresenter
{

	/**
	 * @var \Entity\Rental\Rental
	 */
	protected $rental;

	/**
	 * @autowire
	 * @var \OwnerModule\Forms\IRentalEditFormFactory
	 */
	protected $rentalEditFormFactory;

	/**
	 * @autowire
	 * @var \FormHandler\IRentalEditHandlerFactory
	 */
	protected $rentalEditHandlerFactory;


	public function actionFirstRental()
	{
		$rental = $this->loggedUser->getFirstRental();
		if (!$rental) {
			$this->redirect('User:edit');
		}
		$this->redirect('edit', ['id' => $rental->getId()]);
	}


	public function actionAdd()
	{

	}


	public function actionEdit($id)
	{
		$this->rental = $this->rentalRepositoryAccessor->get()->find($id);

		$this->checkPermission($this->rental, 'edit');

		$rentalEditForm = $this->getComponent('rentalEditForm');
		if(!$rentalEditForm->isSubmitted()){
			$rentalEditForm['rental']['priceList']->setDefaultsValues();
			$rentalEditForm['rental']['priceUpload']->setDefaultsValues();
		}

		//$rentalService = $this->rentalDecoratorFactory->create($this->rental);

		$this->template->rental = $this->rental;
		$this->template->environmentLanguage = $this->environment->getLanguage();

	}


	protected function createComponentRentalEditForm()
	{
		$form = $this->rentalEditFormFactory->create($this->rental, $this->environment);

		$rentalEditHandler = $this->rentalEditHandlerFactory->create($this->rental);
		$rentalEditHandler->attach($form);

		$form->onSuccess[] = function($form) {
			$form->getPresenter()->redirect('this');
		};

		return $form;
	}

}

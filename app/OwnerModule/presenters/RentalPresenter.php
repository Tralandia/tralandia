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
		$this->rental = $this->rentalDao->find($id);

		$this->checkPermission($this->rental, 'edit');

		//$rentalService = $this->rentalDecoratorFactory->create($this->rental);

		$this->template->rental = $this->rental;
		$this->template->environmentLanguage = $this->environment->getLanguage();

		$this->template->onChangeConfirm = $this->translate('152851');

	}


	protected function createComponentRentalEditForm()
	{
		$form = $this->rentalEditFormFactory->create($this->rental, $this->environment);
		$form->getElementPrototype()->addClass('traform dashboard leaveChangeControl');

		$rentalEditHandler = $this->rentalEditHandlerFactory->create($this->rental);
		$rentalEditHandler->attach($form);

		$form->onError[] = function($form) {
			$this->flashMessage(791, $this::FLASH_ERROR);
		};

		$form->onAttached[] = function(\Nette\Application\UI\Form $form, $presenter) {
			$form['rental']['priceList']->setDefaultsValues();
			$form['rental']['priceUpload']->setDefaultsValues();
			if(!$form->isSubmitted()) {
				$form->validate();
			} else {
				$form->getElementPrototype()->addClass('submitted');
			}
		};

		$form->onSubmit[] = function($form) {
			if($form->isValid()) {
				$form->getPresenter()->redirect(':Front:Rental:detail', ['rental' => $this->rental]);
			}
		};



		return $form;
	}

}

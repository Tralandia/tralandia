<?php

namespace OwnerModule;


use Nette\Application\BadRequestException;
use Nette\Application\UI\Form;
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

	/**
	 * @autowire
	 * @var \Tralandia\Location\Countries
	 */
	protected $countries;

	/**
	 * @autowire
	 * @var \Service\Rental\RentalCreator
	 */
	protected $rentalCreator;


	public function actionFirstRental()
	{
		$rental = $this->loggedUser->getFirstRental();
		if (!$rental) {
			$this->redirect('add');
		}
		$this->redirect('RentalEdit:default', ['id' => $rental->getId()]);
	}


	public function actionAdd()
	{

	}


	public function createComponentAddForm()
	{
		$form = $this->simpleFormFactory->create();
		$form->addText('name', $this->translate(152275))
			->setRequired(TRUE);

		$countries = $this->countries->getForSelect();
		$form->addSelect('country', 'o1094', $countries)
			->setDefaultValue($this->loggedUser->getPrimaryLocation()->getId());

		$form->addSubmit('submit', '450090');

		$form->onSuccess[] = $this->addFormSuccess;

		return $form;
	}


	public function addFormSuccess(Form $form)
	{
		$values = $form->getValues();

		$primaryLocation = $this->findLocation($values->country);

		$rental = $this->rentalCreator->simpleCreate($this->loggedUser, $primaryLocation, $values->name);

		$this->rentalDao->add($rental);
		$this->em->flush();

		$this->redirect('edit', ['id' => $rental->getId()]);
	}


	public function actionEdit($id)
	{
		$this->redirect('RentalEdit:default', ['id' => $id]); // pouziva sa uz novy edit
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

		$form->onValidate[] = function($form) {
			$name = $form['rental']['name']->getValues();
			$nameIsFilled = FALSE;
			foreach($name as $key => $value) {
				if(strlen($value)) {
					$nameIsFilled = TRUE;
					break;
				}
			}


			if(!$nameIsFilled) {
				$form['rental']['name']['en']->addError($form->translate('o100071'));
			}

		};

		$form->onError[] = function($form) {
			$this->flashMessage(791, $this::FLASH_ERROR);
		};

		$form->onAttached[] = function(\Nette\Application\UI\Form $form, $presenter) {
			if(!$form->isSubmitted()) {
				$form->validate();
			} else {
				$form->getElementPrototype()->addClass('submitted');
			}
		};

		$form->onSubmit[] = function($form) {
			$form->validate();
			if($form->isValid()) {
				$form->getPresenter()->redirect(':Front:Rental:detail', ['rental' => $this->rental]);
			}
		};



		return $form;
	}

}

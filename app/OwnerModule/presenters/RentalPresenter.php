<?php

namespace OwnerModule;

class RentalPresenter extends BasePresenter {

	protected $rentalRepositoryAccessor;

	protected $rental;

	protected $formFactory;

	public function injectForm(OwnerModule\Forms\IRentalEditFormFactory $formFactory ){
		$this->formFactory = $formFactory;
	}

	public function setContext(\Nette\DI\Container $dic) {
		$this->setProperty('rentalRepositoryAccessor');
	}



	public function actionEdit($id) {
		$this->rental = $this->rentalRepositoryAccessor->get()->find($id);
	}

	protected function createComponentRentalEditForm(){
		$form = $this->formFactory->create($this->rental);
		return $form ;
	}

}

<?php

namespace OwnerModule;

class RentalPresenter extends BasePresenter {

	public $rentalRepositoryAccessor;

	protected $rental;

	protected $formFactory;

	public function injectForm(Forms\IRentalEditFormFactory $formFactory ){
		$this->formFactory = $formFactory;
	}

	public function injectDic(\Nette\DI\Container $dic) {
		$this->rentalRepositoryAccessor = $dic->rentalRepositoryAccessor;
	}

	public function actionAdd(){
		
	}

	public function actionEdit($id) {

		$this->rental = $this->rentalRepositoryAccessor->get()->find($id);		

		//$rentalService = $this->rentalDecoratorFactory->create($this->rental);

		$this->template->rental = $this->rental;
		//$this->template->rentalService = $rentalService;

	}

	protected function createComponentRentalEditForm(){
		$form = $this->formFactory->create($this->rental);
		$form->buildForm();
		return $form ;
	}

}

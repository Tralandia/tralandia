<?php

namespace OwnerModule;


use Nette\Application\BadRequestException;

class RentalPresenter extends BasePresenter {

	public $rentalRepositoryAccessor;

	/**
	 * @var \Entity\Rental\Rental
	 */
	protected $rental;

	/**
	 * @autowire
	 * @var \FrontModule\Forms\Rental\IRentalEditFormFactory
	 */
	protected $rentalEditFormFactory;

	public function injectDic(\Nette\DI\Container $dic) {
		$this->rentalRepositoryAccessor = $dic->rentalRepositoryAccessor;
	}

	public function actionFirstRental() {
		$rental = $this->loggedUser->getFirstRental();
		if(!$rental) {
			throw new BadRequestException('Nenasiel som ziadny objekt');
		}
		$this->redirect('edit', ['id' => $rental->getId()]);
	}

	public function actionAdd(){

	}

	public function actionEdit($id) {
		$this->rental = $this->rentalRepositoryAccessor->get()->find($id);

		if(!$this->getUser()->isAllowed($this->rental, 'edit')) $this->accessDeny();

		//$rentalService = $this->rentalDecoratorFactory->create($this->rental);

		$this->template->rental = $this->rental;
		//$this->template->rentalService = $rentalService;

	}

	protected function createComponentRentalEditForm(){
		$form = $this->rentalEditFormFactory->create($this->rental, $this->environment);

		return $form ;
	}

}

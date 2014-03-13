<?php

namespace OwnerModule;


use BaseModule\Forms\SimpleForm;

class UnitPresenter extends BasePresenter
{

	/**
	 * @var \Entity\Rental\Rental
	 */
	protected $currentRental;

	public function actionDefault($id)
	{
		$this->currentRental = $this->findRental($id);
		// $this->template->environment = $this->environment;
		$this->template->thisRental = $this->currentRental;
		
		// $this->template->rentals = $this->loggedUser->getRentals();
	}

	public function createComponentUnitForm()
	{
		$form = $this->simpleFormFactory->create();
		// $form->addUnitContainer('units', 'Unit', $this->currentRental->getUnits());

		// $form->addSubmit('submit', 'o100083');

		$form->onSuccess[] = $this->processUnitForm;

		// return $form;
	}

	public function processUnitForm(SimpleForm $form)
	{
		// $values = $form->getFormattedValues(TRUE);

		// $rental = $this->currentRental;

		// $rental->updateCalendar($values['calendar']['data']);

		// $this->em->flush($rental);

		// $this->invalidateRentalListener->onSuccess($rental);

		// $this->payload->success = TRUE;
		// $this->sendPayload();
	}

}

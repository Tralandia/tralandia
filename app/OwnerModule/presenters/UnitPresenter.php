<?php

namespace OwnerModule;


use BaseModule\Forms\SimpleForm;
use Nette\Application\UI\Multiplier;

class UnitPresenter extends BasePresenter
{

	/**
	 * @var \Entity\Rental\Rental
	 */
	protected $currentRental;

	protected $rentals;

	public function actionDefault()
	{
		$this->template->rentals = $this->rentals = $this->loggedUser->getRentals();
	}

	public function createComponentUnitForm()
	{
		$form = $this->simpleFormFactory->create();
		foreach($this->rentals as $rental) {
			$form->addDynamic($rental->getId(), $this->rentalUnitsBuilder, 1);
		}

		$form->addSubmit('submit', '!!!ulozit');

		$this->setDefaults($form);

		return $form;
	}

	public function rentalUnitsBuilder(\Nette\Forms\Container $container)
	{
		$container->addText('name', '');
		$container->addText('maxCapacity', '');
		$container->addHidden('entityId', '');
	}

	protected function setDefaults(\BaseModule\Forms\SimpleForm $form)
	{
		$defaults = [];
		foreach($this->rentals as $rental) {

		}
		$form->setDefaults($defaults);
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

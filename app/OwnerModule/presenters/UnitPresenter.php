<?php

namespace OwnerModule;


use BaseModule\Forms\SimpleForm;

class UnitPresenter extends BasePresenter
{

	/**
	 * @autowire
	 * @var \Tralandia\Rental\RentalRepository
	 */
	protected $rentalRepository;

	/**
	 * @autowire
	 * @var \Tralandia\Rental\UnitRepository
	 */
	protected $unitRepository;

	/**
	 * @var \Tralandia\Rental\Rental[]
	 */
	protected $rentals;

	public function actionDefault()
	{
		$this->template->rentals = $this->rentals = $this->rentalRepository->findBy(['user_id' => $this->loggedUser->getId()]);
	}

	public function createComponentUnitForm()
	{
		$form = $this->simpleFormFactory->create();
		foreach($this->rentals as $rental) {
			$form->addDynamic($rental->getId(), $this->rentalUnitsBuilder, 1);
		}

		$form->addSubmit('submit', 559);


		$form->onAttached[] = function($form) {
			$this->setDefaults($form);
		};

		$form->onValidate[] = $this->validateUnitForm;
		$form->onSuccess[] = $this->processUnitForm;
		$form->onSuccess[] = function($form) {
			$this->redirect('this');
		};

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
			foreach($rental->units as $unit) {
				$defaults[$rental->id][] = [
					'name' => $unit->name,
					'maxCapacity' => $unit->maxCapacity,
					'entityId' => $unit->id,
				];
			}

		}
		$form->setDefaults($defaults);
	}

	public function validateUnitForm(SimpleForm $form)
	{
		$values = $form->getFormattedValues(TRUE);
		foreach($this->rentals as $rental) {
			$rentalValues = $values[$rental->getId()];
			$unitCount = 0;
			foreach($rentalValues as $unitValue) {
				if(!$unitValue['name'] && !$unitValue['maxCapacity']) continue;
				unset($unit);

				$unitCount++;
			}

			if($unitCount == 0) {
				$form->addError('!!!Please set at leas one Unit to each Rental');
			}
		}

	}

	public function processUnitForm(SimpleForm $form)
	{
		$values = $form->getFormattedValues(TRUE);

		foreach($this->rentals as $rental) {
			$rentalValues = $values[$rental->getId()];
			$oldUnits = $rental->units;
			$currentIds = [];
			foreach($rentalValues as $unitValue) {
				if(!$unitValue['name'] && !$unitValue['maxCapacity']) continue;
				unset($unit);

				if($unitValue['entityId']) {
					$unit = $this->unitRepository->find($unitValue['entityId']);
					$currentIds[$unitValue['entityId']] = TRUE;
				}

				if(!isset($unit) || !$unit) {
					$unit = $this->unitRepository->createNew();
					$unit->setRental($rental);
				}

				$unit->setName($unitValue['name']);
				$unit->setMaxCapacity($unitValue['maxCapacity']);

				$this->unitRepository->save($unit);
			}

			foreach($oldUnits as $unit) {
				if(array_key_exists($unit->id, $currentIds)) continue;

				$this->unitRepository->delete($unit);
			}
		}

	}

}

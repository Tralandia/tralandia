<?php

namespace OwnerModule;


use BaseModule\Forms\BaseForm;

class ReservationEditPresenter extends BasePresenter
{
	/**
	 * @autowire
	 * @var \OwnerModule\Forms\IOrderManagerEditFormFactory
	 */
	protected $orderManagerEditFormFactory;

	/**
	 * @autowire
	 * @var \Tralandia\Location\Countries
	 */
	protected $countries;

	public function createComponentEditForm()
	{
		$form = $this->simpleFormFactory->create();

		$form->addRentalUnitContainer('units', 'Ubytovacie jednotky', $this->user);

		$form->addText('name', '!!!Meno')
			->setRequired('Povinne policko');

		$form->addText('surname', '!!!Priezvisko')
			->setRequired('Povinne policko');

		$form->addText('email', 'Emailova adresa')
			->setRequired('Povinne policko');

		$form->addPhoneContainer('phone', 'Telefon', $this->countries->getPhonePrefixes());

		$form->addText('peopleCount', 'Pocet dospelych')
			->setRequired('Povinne policko')
			->addRule(BaseForm::NUMERIC, 'Musi byt cislo');

		$form->addText('childrenCount', 'Pocet deti')
			->setRequired('Povinne policko')
			->addRule(BaseForm::NUMERIC, 'Musi byt cislo');

		$form->addText('childrenAge', 'Vek deti');

		$form->addText('dateFrom', 'Datum od')
			->setRequired('Povinne policko');

		$form->addText('dateTo', 'Datum do')
			->setRequired('Povinne policko');

		$form->addTextArea('message', 'Sprava');
		$form->addTextArea('ownerNotes', 'Poznamky');

		$form->addCheckbox('confirmed', 'Rezervacia potvrdena');

		$form->addText('referer', 'Zdroj rezervacie');

		$form->addText('price', 'Konecna cena')
			->setRequired('Povinne policko')
			->addRule(BaseForm::NUMERIC, 'Musi byt cislo');

		$form->addText('price_paid', 'Uhradena suma')
			->setRequired('Povinne policko')
			->addRule(BaseForm::NUMERIC, 'Musi byt cislo');

		$form->addSubmit('submit', 'o100151');


		$form->onAttached[] = function($form) {
			$this->setDefaults($form);
		};

		$form->onSuccess[] = $this->processEditForm;
		$form->onSuccess[] = function($form) {
			$this->redirect('this');
		};

		return $form;
	}

}

<?php

namespace OwnerModule;


use BaseModule\Forms\BaseForm;
use Nette\Application\UI\Form;

class ReservationEditPresenter extends BasePresenter
{

	/**
	 * @autowire
	 * @var \Tralandia\Location\Countries
	 */
	protected $countries;

	/**
	 * @autowire
	 * @var \Tralandia\Currency\Currencies
	 */
	protected $currencies;

	/**
	 * @var \Entity\User\RentalReservation
	 */
	protected $reservation;


	/**
	 * @persistent
	 * @var string
	 */
	public $backlink;


	public function actionDefault($id)
	{
		$this->reservation = $this->findReservation($id);
		$this->checkPermission($this->reservation, 'edit');
		$this->template->reservation = $this->reservation;
	}

	public function createComponentEditForm()
	{
		$form = $this->simpleFormFactory->create();

		$form->addRentalUnitContainer('units', 'Ubytovacie jednotky', $this->loggedUser->getRentals());

		$form->addText('senderName', 719637);

		$form->addText('senderEmail', 522);

		$form->addPhoneContainer('senderPhone', 581, $this->countries->getPhonePrefixes());

		$form->addText('adultsCount', 596)
			->addCondition(BaseForm::FILLED)
			->addRule(BaseForm::INTEGER, 'Musi byt cislo');

		$form->addText('childrenCount', 597)
			->addCondition(BaseForm::FILLED)
			->addRule(BaseForm::INTEGER, 'Musi byt cislo');

		$form->addText('childrenAge', 719638);

		$form->addAdvancedDatePicker('arrivalDate', 599);

		$form->addAdvancedDatePicker('departureDate', 600);

		$form->addTextArea('message', 610);
		$form->addTextArea('ownersNote', 719639, null, 5);

		$form->addSelect('status', $this->translate(719636), [
			\Entity\User\RentalReservation::STATUS_CONFIRMED => '!!!potvrdena',
			\Entity\User\RentalReservation::STATUS_OPENED => '!!!otvorena',
			\Entity\User\RentalReservation::STATUS_CANCELED => '!!!zrusena',
		])->setPrompt('--!!!status--');

		$form->addText('referrer', 719640);

		$form->addSelect('currency', '!mena', $this->currencies->getForSelect());

		$form->addText('totalPrice', 'Konecna cena')
			->addCondition(BaseForm::FILLED)
			->addRule(BaseForm::FLOAT, 'Musi byt cislo');

		$form->addText('paidPrice', 'Uhradena suma')
			->addCondition(BaseForm::FILLED)
			->addRule(BaseForm::FLOAT, 'Musi byt cislo');

		$form->addSubmit('submit', 'o100151');


		$form->onAttached[] = function($form) {
			$this->setDefaults($form);
		};

		$form->onValidate[] = $this->validate;
		$form->onSuccess[] = $this->processEditForm;
		$form->onSuccess[] = function($form) {
			$this->redirect('ReservationManager:list', ['restoreSearch' => $this->backlink]);
		};

		return $form;
	}

	protected function setDefaults(Form $form)
	{
		$reservation = $this->reservation;
		$defaults = [
			'senderName' => $reservation->getSenderName(),
			'senderEmail' => $reservation->getSenderEmail(),
			'senderPhone' => $reservation->getSenderPhone(),
			'adultsCount' => $reservation->getAdultsCount(),
			'childrenCount' => $reservation->getChildrenCount(),
			'childrenAge' => $reservation->getChildrenAge(),
			'arrivalDate' => $reservation->getArrivalDate(),
			'departureDate' => $reservation->getDepartureDate(),
			'message' => $reservation->getMessage(),
			'ownersNote' => $reservation->getOwnersNote(),
			'status' => $reservation->getStatus(),
			'referrer' => $reservation->getReferrer(),
			'totalPrice' => $reservation->getTotalPrice(),
			'paidPrice' => $reservation->getPaidPrice(),
			'currency' => $reservation->getSomeCurrency()->id,
			'units' => $reservation->getUnits(),
		];

		$form->setDefaults($defaults);
	}


	public function validate(Form $form)
	{
		$values = $form->getFormattedValues();

		if(!count($values['units'])) {
			$form['units']['mainControl']->addError('!!! vyber aspon jednu moznost');
		}
	}

	public function processEditForm(Form $form)
	{
		$values = $form->getFormattedValues();

		/** @var $reservation \Entity\User\RentalReservation */
		$reservation = $this->reservation;

		$reservation->setSenderName($values['senderName']);
		$reservation->setSenderEmail($values['senderEmail']);
		if($values['senderPhone']['entity']) $reservation->setSenderPhone($values['senderPhone']['entity']);
		$reservation->setAdultsCount((int)$values['adultsCount']);
		$reservation->setChildrenCount((int)$values['childrenCount']);
		$reservation->setChildrenAge($values['childrenAge']);
		if($values['arrivalDate']) $reservation->setArrivalDate($values['arrivalDate']);
		if($values['departureDate']) $reservation->setDepartureDate($values['departureDate']);
		$reservation->setMessage($values['message']);
		$reservation->setOwnersNote($values['ownersNote']);
		$reservation->setStatus($values['status']);
		$reservation->setReferrer($values['referrer']);
		$reservation->setTotalPrice((float)$values['totalPrice']);
		$reservation->setPaidPrice((float)$values['paidPrice']);

		$currency = $this->em->getRepository(CURRENCY_ENTITY)->find($values['currency']);
		$reservation->setCurrency($currency);

		$units = $this->em->getRepository(UNIT_ENTITY)->findBy(['id' => $values['units']]);
		foreach($units as $unit) {
			$reservation->addUnit($unit);
		}


		$this->em->persist($reservation);
		$this->em->flush($reservation);
	}

}

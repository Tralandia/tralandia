<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 05/03/14 14:06
 */

namespace PersonalSiteModule\Contact;


use Nette;
use PersonalSiteModule\BaseControl;
use Tralandia\Rental\Rental;

class ContactControl extends BaseControl
{

	/**
	 * @var Rental
	 */
	private $rental;

	/**
	 * @var \FrontModule\Forms\Rental\IReservationFormFactory
	 */
	protected $reservationFormFactory;


	public function __construct(Rental $rental, \FrontModule\Forms\Rental\IReservationFormFactory $reservationFormFactory)
	{
		parent::__construct();
		$this->rental = $rental;
		$this->reservationFormFactory = $reservationFormFactory;
	}

	public function render()
	{
		$rental = $this->rental;

		$this->template->rental = $rental;

		$formattedCalendar = [];
		foreach($rental->getCalendar() as $day) {
			$formattedCalendar[] = $day->format('d-m-Y');
		}

		$this->template->formatedCalendar = implode(',', $formattedCalendar);

		$this->template->render();
	}

	protected function createComponentReservationForm()
	{
		$rental = $this->presenter->findRental($this->rental->id);
		$form = $this->reservationFormFactory->create($rental);
		//$form->buildForm();

		$form->onSuccess[] = function ($form) {
			//$form->presenter->redirect('this');
			$form->presenter->template->fromSuccessMessage = 'o1029';
			$form->presenter->invalidateControl('reservationForm');
		};

		return $form;
	}


}

interface IContactControlFactory
{

	public function create(Rental $rental);
}
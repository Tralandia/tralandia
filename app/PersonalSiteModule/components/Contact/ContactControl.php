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
	protected $rental;

	/**
	 * @var \FrontModule\Forms\Rental\IReservationFormFactory
	 */
	protected $reservationFormFactory;

	/**
	 * @var null
	 */
	private $fireConversions;


	public function __construct(Rental $rental, $fireConversions, \FrontModule\Forms\Rental\IReservationFormFactory $reservationFormFactory)
	{
		parent::__construct();
		$this->rental = $rental;
		$this->reservationFormFactory = $reservationFormFactory;
		$this->fireConversions = $fireConversions;
	}

	public function render()
	{
		$rental = $this->rental;

		$this->template->rental = $rental;

		$formattedCalendar = [];
		foreach($rental->getCalendar() as $day) {
			$formattedCalendar[] = $day['d']->format('d-m-Y');
		}

		$this->template->formatedCalendar = implode(',', $formattedCalendar);
//		$this->template->reservationSent = FALSE;

		$this->template->render();
	}

	protected function createComponentReservationForm()
	{
		$rental = $this->presenter->findRental($this->rental->id);
		$form = $this->reservationFormFactory->create($rental);
		$form->setMethod("GET");

		$form->onSuccess[] = function ($form) {
			//$form->presenter->redirect('this');
			$form->parent->template->reservationSent = TRUE;
			$this->template->fireConversions = $this->fireConversions;
			$this->template->conversionOnReservation = $this->rental->personalSiteConfiguration->conversionOnReservation;
			$form->parent->invalidateControl('reservationForm');
		};

		return $form;
	}


}

interface IContactControlFactory
{

	public function create(Rental $rental);
}

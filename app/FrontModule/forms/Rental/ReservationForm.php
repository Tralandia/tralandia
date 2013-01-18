<?php

namespace FrontModule\Forms\Rental;

use Nette;

/**
 * ReservationForm class
 *
 * @author Dávid Ďurika
 */
class ReservationForm extends \FrontModule\Forms\BaseForm {

	/**
	 * @var \Entity\Rental\Rental
	 */
	protected $rental;

	/**
	 * @var \Repository\Location\LocationRepository
	 */
	protected $locationRepository;

	/**
	 * @param \Entity\Rental\Rental $rental
	 * @param \Repository\Location\LocationRepository $locationRepository
	 */
	public function __construct(\Entity\Rental\Rental $rental, \Repository\Location\LocationRepository $locationRepository)
	{
		$this->rental = $rental;
		$this->locationRepository = $locationRepository;

		parent::__construct();
	}

	public function buildForm()
	{
		$this->addText('name');
		$this->addText('email');
		$this->addText('from');
		$this->addText('to');

		$this->addSelect('phonePrefix', '', $this->locationRepository->getCountriesPhonePrefixes());

		$this->addText('phone2');

		$parents = array();

		for($i = 0 ; $i < 21 ; ++$i) {
			$parents[$i] = $i;
		}

		$this->addSelect('parents','',$parents)->setPrompt('o12277');
		$this->addSelect('childs','',$parents)->setPrompt('o2443');

		$this->addTextArea('message');

		$this->onSuccess[] = callback($this, 'process');
	}

	public function setDefaultsValues() 
	{
		
	}

	public function process(ReservationForm $form)
	{
		$values = $form->getValues();
	}

}

interface IReservationFormFactory {
	/**
	 * @param \Entity\Rental\Rental $rental
	 *
	 * @return ReservationForm
	 */
	public function create(\Entity\Rental\Rental $rental);
}
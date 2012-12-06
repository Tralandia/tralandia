<?php

namespace FrontModule\Forms\Rental;

use Nette;

/**
 * ReservationForm class
 *
 * @author Dávid Ďurika
 */
class ReservationForm extends \FrontModule\Forms\BaseForm {

	public function __construct()
	{
		parent::__construct();
	}


	protected function buildForm()
	{
		$this->addText('name', 'Name:');
	}

	public function setDefaultsValues() 
	{
		$this->onSuccess[] = callback($this, 'process');
	}

	public function process(ReservationForm $form)
	{
		$values = $form->getValues();
	}

}
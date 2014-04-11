<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 11/04/14 08:13
 */

namespace OwnerModule\RentalEdit;


use BaseModule\Forms\ISimpleFormFactory;
use Entity\Rental\Rental;
use Nette;

class AboutForm extends BaseFormControl
{

	/**
	 * @var \Entity\Rental\Rental
	 */
	private $rental;

	/**
	 * @var \BaseModule\Forms\ISimpleFormFactory
	 */
	private $formFactory;


	public function __construct(Rental $rental, ISimpleFormFactory $formFactory)
	{
		parent::__construct();
		$this->rental = $rental;
		$this->formFactory = $formFactory;
	}


	public function createComponentForm()
	{
		$form = $this->formFactory->create();

		return $form;
	}


}


interface IAboutFormFactory
{
	public function create(\Tralandia\Rental\Rental $rental);
}

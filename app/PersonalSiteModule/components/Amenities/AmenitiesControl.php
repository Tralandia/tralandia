<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 05/03/14 14:06
 */

namespace PersonalSiteModule\Amenities;


use Nette;
use PersonalSiteModule\BaseControl;
use Tralandia\Rental\Rental;

class AmenitiesControl extends BaseControl
{

	/**
	 * @var Rental
	 */
	private $rental;


	public function __construct(Rental $rental)
	{
		parent::__construct();
		$this->rental = $rental;
	}

	public function render()
	{
		$rental = $this->rental;

		$this->template->rental = $rental;

		$this->template->render();
	}

}

interface IAmenitiesControlFactory
{

	public function create(Rental $rental);
}

<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 05/03/14 14:06
 */

namespace PersonalSiteModule\Amenities;


use Nette;
use PersonalSiteModule\BaseControl;
use PersonalSite\RentalData;

class AmenitiesControl extends BaseControl
{

	/**
	 * @var \PersonalSite\RentalData
	 */
	private $rentalData;


	public function __construct(RentalData $rentalData)
	{
		parent::__construct();
		$this->rentalData = $rentalData;
	}

	public function render()
	{
		$rental = $this->rentalData;

		$this->template->rental = $rental;

		$this->template->render();
	}

}

interface IAmenitiesControlFactory
{

	public function create(RentalData $rentalData);
}

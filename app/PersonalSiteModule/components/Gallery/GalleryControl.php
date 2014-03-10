<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 05/03/14 14:06
 */

namespace PersonalSiteModule\Gallery;


use Nette;
use PersonalSiteModule\BaseControl;
use PersonalSite\RentalData;

class GalleryControl extends BaseControl
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

interface IGalleryControlFactory
{
	public function create(RentalData $rentalData);
}

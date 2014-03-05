<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 05/03/14 14:06
 */

namespace PersonalSiteModule\WelcomeScreen;


use Nette;
use PersonalSiteModule\BaseControl;
use Tralandia\PersonalSite\RentalData;

class WelcomeScreenControl extends BaseControl
{

	/**
	 * @var \Tralandia\PersonalSite\RentalData
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
		$this->template->name = $rental->getName();
		$this->template->render();
	}

}

interface IWelcomeScreenControlFactory
{

	/**
	 * @param RentalData $rentalData
	 *
	 * @return \PersonalSiteModule\WelcomeScreen\WelcomeScreenControl
	 */
	public function create(RentalData $rentalData);
}

<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 05/03/14 14:06
 */

namespace PersonalSiteModule\WelcomeScreen;


use Nette;
use PersonalSiteModule\BaseControl;
use Tralandia\Rental\Rental;

class WelcomeScreenControl extends BaseControl
{

	/**
	 * @var \Tralandia\Rental\Rental
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

interface IWelcomeScreenControlFactory
{

	public function create(Rental $rental);
}
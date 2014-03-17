<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 05/03/14 14:06
 */

namespace PersonalSiteModule\Prices;


use Nette;
use PersonalSiteModule\BaseControl;
use Tralandia\Rental\Rental;

class PricesControl extends BaseControl
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

interface IPricesControlFactory
{
	public function create(Rental $rental);
}

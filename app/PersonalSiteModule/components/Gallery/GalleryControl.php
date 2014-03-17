<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 05/03/14 14:06
 */

namespace PersonalSiteModule\Gallery;


use Nette;
use PersonalSiteModule\BaseControl;
use Tralandia\Rental\Rental;

class GalleryControl extends BaseControl
{

	/**
	 * @var \Tralandia\Rental\Rental
	 */
	private $rental;


	public function __construct(Rental $rental)
	{
		\MyTimer::log('gallery');
		parent::__construct();
		$this->rental = $rental;
	}

	public function render()
	{
		$rental = $this->rental;

		$this->template->rental = $rental;

		$this->template->render();
		\MyTimer::log('gallery');
	}

}

interface IGalleryControlFactory
{
	public function create(Rental $rental);
}

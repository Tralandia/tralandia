<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 11/04/14 08:13
 */

namespace OwnerModule\RentalEdit;


use Nette;

abstract class BaseFormControl extends \BaseModule\Components\BaseFormControl
{

	/**
	 * @var \Entity\Rental\Rental
	 */
	protected $rental;


	public function createTemplate($class = null)
	{
		$template = parent::createTemplate($class);
		$template->onChangeConfirm = $this->translate('152851');

		return $template;
	}


	/**
	 * @return \Entity\Rental\Rental
	 */
	public function getRental()
	{
		return $this->rental;
	}
}
